<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

/**
 * PhotoCategories Model
 *
 * @property \App\Model\Table\PhotosTable&\Cake\ORM\Association\HasMany $Photos
 *
 * @method \App\Model\Entity\PhotoCategory newEmptyEntity()
 * @method \App\Model\Entity\PhotoCategory newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\PhotoCategory> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PhotoCategory get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\PhotoCategory findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\PhotoCategory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\PhotoCategory> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\PhotoCategory|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\PhotoCategory saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\PhotoCategory>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\PhotoCategory>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\PhotoCategory>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\PhotoCategory> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\PhotoCategory>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\PhotoCategory>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\PhotoCategory>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\PhotoCategory> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PhotoCategoriesTable extends AppTable
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

        $this->setTable('photo_categories');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addTranslate(['name']);

        $this->hasMany('Photos', [
            'foreignKey' => 'photo_category_id',
        ]);
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
            ->notEmptyString('name');

        $validator
            ->scalar('slug')
            ->maxLength('slug', 100)
            ->requirePresence('slug', 'create')
            ->notEmptyString('slug')
            ->add('slug', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->nonNegativeInteger('photos_count')
            ->notEmptyString('photos_count');

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
        $rules->add($rules->isUnique(['slug']), ['errorField' => 'slug']);

        return $rules;
    }
}
