<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\I18n\DateTime;
use Cake\ORM\Query\SelectQuery;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;

/**
 * BlogPosts Model
 *
 * @property \App\Model\Table\PhotosTable&\Cake\ORM\Association\BelongsToMany $Photos
 *
 * @method \App\Model\Entity\BlogPost newEmptyEntity()
 * @method \App\Model\Entity\BlogPost newEntity(array $data, array $options = [])
 * @method array<\App\Model\Entity\BlogPost> newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\BlogPost get(mixed $primaryKey, array|string $finder = 'all', \Psr\SimpleCache\CacheInterface|string|null $cache = null, \Closure|string|null $cacheKey = null, mixed ...$args)
 * @method \App\Model\Entity\BlogPost findOrCreate($search, ?callable $callback = null, array $options = [])
 * @method \App\Model\Entity\BlogPost patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method array<\App\Model\Entity\BlogPost> patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\BlogPost|false save(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method \App\Model\Entity\BlogPost saveOrFail(\Cake\Datasource\EntityInterface $entity, array $options = [])
 * @method iterable<\App\Model\Entity\BlogPost>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\BlogPost>|false saveMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\BlogPost>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\BlogPost> saveManyOrFail(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\BlogPost>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\BlogPost>|false deleteMany(iterable $entities, array $options = [])
 * @method iterable<\App\Model\Entity\BlogPost>|\Cake\Datasource\ResultSetInterface<\App\Model\Entity\BlogPost> deleteManyOrFail(iterable $entities, array $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class BlogPostsTable extends AppTable
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

        $this->setTable('blog_posts');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addTranslate(['title', 'body']);

        $this->belongsToMany('Photos', [
            'foreignKey' => 'blog_post_id',
            'targetForeignKey' => 'photo_id',
            'through' => 'BlogPostsPhotos',
            'sort' => ['BlogPostsPhotos.pos' => 'ASC', 'BlogPostsPhotos.id' => 'ASC'],
        ]);
        $this->hasMany('BlogPostsPhotos', [
            'foreignKey' => 'blog_post_id',
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
            ->scalar('slug')
            ->maxLength('slug', 150)
            ->requirePresence('slug', 'create')
            ->notEmptyString('slug')
            ->add('slug', 'unique', ['rule' => 'validateUnique', 'provider' => 'table']);

        $validator
            ->scalar('title')
            ->maxLength('title', 255)
            ->requirePresence('title', 'create')
            ->notEmptyString('title');

        $validator
            ->scalar('body')
            ->allowEmptyString('body');

        $validator
            ->dateTime('published')
            ->allowEmptyDateTime('published');

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

    /**
     * Published blog posts for the public site.
     *
     * @param \Cake\ORM\Query\SelectQuery $query Query.
     * @return \Cake\ORM\Query\SelectQuery
     */
    public function findPublished(SelectQuery $query): SelectQuery
    {
        return $query
            ->where([
                'BlogPosts.visible' => true,
                'BlogPosts.published IS NOT' => null,
                'BlogPosts.published <=' => DateTime::now(),
            ])
            ->contain(['Photos' => ['Tags']])
            ->orderBy([
                'BlogPosts.published' => 'DESC',
                'BlogPosts.pos' => 'ASC',
                'BlogPosts.id' => 'DESC',
            ]);
    }
}
