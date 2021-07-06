<?php

declare(strict_types=1);

namespace App\DataFixtures;

use ArrayObject;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Exception;
use Doctrine\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use JsonMapper;
use JsonMapper_Exception;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Yaml\Yaml;

/**
 * Abstract base class for data fixtures.
 *
 * @author Alexander Serbe <codiersklave@yahoo.de>
 * @author Michael Kissinger <aquakami2005@googlemail.com>
 */
abstract class AbstractFixture extends Fixture
{
    /**
     * @var JsonMapper $mapper
     */
    private JsonMapper $mapper;
    
    /**
     * @var EntityManagerInterface $entityManager
     */
    protected EntityManagerInterface $entityManager;
    
    /**
     * @var KernelInterface
     */
    private KernelInterface $appKernel;
    
    /**
     * @param EntityManagerInterface $entityManager
     * @param JsonMapper $mapper
     * @param KernelInterface $appKernel
     */
    public function __construct(EntityManagerInterface $entityManager, JsonMapper $mapper, KernelInterface $appKernel)
    {
        $this->mapper = $mapper;
        $this->mapper->bEnforceMapType = false;
        $this->entityManager = $entityManager;
        $this->appKernel = $appKernel;
    }
    
    /**
     * @param ObjectManager $manager
     *
     * @throws JsonMapper_Exception
     * @throws Exception
     */
    public function load(ObjectManager $manager)
    {
        $dataFiles = $this->getFixtureFiles();
        $dir = $this->appKernel->getProjectDir() . '/src/DataFixtures/data/';
        
        foreach ($dataFiles as $dataFile) {
            if (strpos($dataFile, '.csv')) {
                $lines = file($dir . $dataFile);
                $lineCount = 0;
                $keys = [];
                $data = [];
                
                foreach ($lines as $line) {
                    if (strlen(trim($line)) === 0) {
                        continue;
                    }
                    
                    $values = str_getcsv($line, ';');
                    
                    if ($lineCount === 0) {
                        $keys = $values;
                    } else {
                        $name = $values[0];
                        
                        $data[$name] = [
                            'data' => [],
                            'references' => [],
                        ];
                        
                        for ($i = 1; $i < count($values); $i++) {
                            $propName = isset($keys[$i]) ? $keys[$i] : null;
                            
                            if ($propName === null) {
                                continue;
                            }
                            
                            $propValue = $values[$i];
                            
                            if (str_contains($propName, 'REF:')) {
                                if ($propValue !== null && strlen(trim($propValue)) > 0) {
                                    $data[$name]['references'][substr($propName, strlen('REF:'))] = $propValue;
                                }
                            } elseif (str_contains($propName, 'JSON:')) {
                                if ($propValue !== null && strlen(trim($propValue)) > 0) {
                                    $data[$name]['data'][substr($propName, strlen('JSON:'))] = json_decode($propValue);
                                }
                            } else {
                                if (strlen(trim($propValue)) > 0) {
                                    $data[$name]['data'][$propName] = $propValue;
                                }
                            }
                        }
                    }
                    
                    $lineCount++;
                }
            } else {
                $data = Yaml::parseFile($dir . $dataFile);
            }
            
            $entityClass = $this->getEntityClass();
            
            if (str_contains($dataFile, '.sql')) {
                $tableName = str_replace('.sql', '', $dataFile);
                $tableName = str_replace('.yml', '', $tableName);
                $tableName = str_replace('.csv', '', $tableName);
                
                foreach ($data as $name => $fixture) {
                    $sql = "INSERT INTO `{$tableName}` (";
                    $sql .= implode(', ', array_keys($fixture['data']));
                    
                    if (count($fixture['references']) > 0) {
                        $sql .= ', ';
                        $sql .= implode(', ', array_keys($fixture['references']));
                    }
                    
                    foreach ($fixture['data'] as $key => $value) {
                        $fixture['data'][$key] = trim(str_replace("'", "\'", $value));
                    }
                    
                    $sql .= ') VALUES (\'' . implode('\', \'', $fixture['data']);
                    
                    if (count($fixture['references']) > 0) {
                        $sql .= '\', \'';
                        $refIds = [];
                        
                        foreach ($fixture['references'] as $col => $alias) {
                            $ref = $this->getReference($alias);
                            $refIds[] = $ref->getId();
                        }
                        
                        $sql .= implode('\', \'', $refIds);
                    }
                    
                    $sql .= '\')';
                    
                    $this->entityManager->getConnection()->exec($sql);
                    
                    $repo = $this->entityManager->getRepository($entityClass);
                    
                    if (array_key_exists('id', array_keys($fixture['data']))) {
                        $entity = $repo->find($fixture['data']['id']);
                    } else {
                        $id = $this->entityManager->getConnection()->lastInsertId();
                        $entity = $repo->find($id);
                    }
                    
                    if ($entity && substr($name, 0, 1) !== '_') {
                        $this->addReference($name, $entity);
                    }
                }
            } else {
                foreach ($data as $name => $fixture) {
                    $fixtureData = $fixture['data'];
                    $references = ($fixture['references'] ?? []);
                    
                    if ($fixtureData === null) {
                        $fixtureData = [];
                    }
                    
                    $entity = $this->mapToEntity($fixtureData, new $entityClass());
                    
                    foreach ($references as $property => $aliases) {
                        $setter = 'set'.$property;
                        
                        if (is_array($aliases)) {
                            $referencedEntities = [];
                            
                            foreach ($aliases as $alias) {
                                $referencedEntities[] = $this->getReference($alias);
                            }
                            
                            $entity->$setter(new ArrayCollection($referencedEntities));
                        } else {
                            $entity->$setter($this->getReference($aliases));
                        }
                    }
                    
                    $manager->persist($entity);
                    $manager->flush();
                    
                    if (substr($name, 0, 1) !== '_') {
                        $this->addReference($name, $entity);
                    }
                    
                    if ($this instanceof PostPersistListener) {
                        $this->postPersist($fixtureData, $entity);
                    }
                }
            }
        }
    }
    
    /**
     * @param array $fixtureData
     * @param $entity
     *
     * @return mixed
     *
     * @throws JsonMapper_Exception
     */
    protected function mapToEntity(array $fixtureData, $entity): mixed
    {
        return $this->mapper->map(new ArrayObject($fixtureData), $entity);
    }
    
    /**
     * @return string
     */
    abstract protected function getEntityClass(): string;
    
    /**
     * @return array
     */
    abstract protected function getFixtureFiles(): array;
}
