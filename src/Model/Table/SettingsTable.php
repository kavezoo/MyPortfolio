<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

/**
 * Settings Model
 *
 * @method \App\Model\Entity\Setting newEmptyEntity()
 * @method \App\Model\Entity\Setting newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\Setting> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Setting get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\Setting findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\Setting patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\Setting> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Setting|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\Setting saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\Setting>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Setting>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Setting>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Setting> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Setting>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Setting>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\Setting>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\Setting> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SettingsTable extends AppTable
{
    /**
     * Initialize method
     *
     * @param array<string, mixed> $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('settings');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addTranslate(['label', 'value']);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->scalar('name')
            ->maxLength('name', 100)
            ->requirePresence('name', 'create')
            ->notEmptyString('name')
            ->add('name', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('label')
            ->maxLength('label', 255)
            ->requirePresence('label', 'create')
            ->notEmptyString('label');

        $validator
            ->scalar('value')
            ->allowEmptyString('value');

        $validator
            ->boolean('visible')
            ->notEmptyString('visible');

        $validator
            ->integer('pos')
            ->notEmptyString('pos');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->isUnique(['name']), ['errorField' => 'name']);

        return $rules;
    }

    /**
     * Settings as a name => value map.
     *
     * @return array<string, string|null>
     */
    public function asMap(): array
    {
        return $this->find('visible')
            ->all()
            ->combine('name', 'value')
            ->toArray();
    }
}
