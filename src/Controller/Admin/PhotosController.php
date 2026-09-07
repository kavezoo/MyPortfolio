<?php
declare(strict_types=1);

namespace App\Controller\Admin;

use App\Service\PhotoFileService;
use Cake\Http\Exception\NotFoundException;
use Cake\Utility\Text;
use Psr\Http\Message\UploadedFileInterface;

/**
 * Photos Controller
 *
 * @property \App\Model\Table\PhotosTable $Photos
 */
class PhotosController extends AppController
{
    /**
     * @var \App\Service\PhotoFileService
     */
    protected PhotoFileService $photoFiles;

    /**
     * Initialize controller
     *
     * @return void
     */
    public function initialize(): void
    {
        parent::initialize();
        $this->photoFiles = new PhotoFileService();
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $queryParams = $this->getRequest()->getQueryParams();

        if (isset($queryParams['clear']) && $queryParams['clear'] === 'filter') {
            $this->session->delete('Paging.Photos.params');

            return $this->redirect(['action' => 'index']);
        }

        if (isset($queryParams['clear']) && $queryParams['clear'] === 'search') {
            $this->session->delete('Paging.Photos.params');
            $redirectParams = $queryParams;
            unset($redirectParams['clear'], $redirectParams['search'], $redirectParams['page']);

            return $this->redirect([
                'action' => 'index',
                '?' => $redirectParams ?: null,
            ]);
        }

        $searchableFields = [
            'Photos.uuid',
            'Photos.slug',
            'Photos.code',
            'Photos.filename',
            'Photos.title',
            'Photos.location',
            'Photos.city',
            'Photos.camera',
            'Photos.lens',
            'PhotoCategories.name',
        ];

        $this->paginate = [
            'limit' => 20,
            'maxLimit' => 100,
        ];

        if (empty($queryParams) && $this->session->check('Paging.Photos.params')) {
            $savedParams = (array)$this->session->read('Paging.Photos.params');
            if (!empty($savedParams) && !array_key_exists('parent_filter', $savedParams)) {
                return $this->redirect([
                    'action' => 'index',
                    '?' => $savedParams,
                ]);
            }
        }

        $query = $this->Photos->find()->contain(['PhotoCategories']);

        $parentContext = null;
        $parentFilter = isset($queryParams['parent_filter']) ? (string)$queryParams['parent_filter'] : null;
        foreach ($this->Photos->associations()->getByType('BelongsTo') as $association) {
            if ($parentFilter === null || $parentFilter !== $association->getName()) {
                continue;
            }

            $foreignKey = $association->getForeignKey();
            if (!is_string($foreignKey)) {
                continue;
            }

            $filterValue = $queryParams[$foreignKey] ?? null;
            if ($filterValue === null || $filterValue === '') {
                continue;
            }

            $query->where(['Photos.' . $foreignKey => $filterValue]);
            $parentTable = $association->getTarget();
            try {
                $parentEntity = $this->fetchTable($parentTable->getAlias())->get($filterValue);
            } catch (\Cake\Datasource\Exception\RecordNotFoundException $e) {
                throw new NotFoundException(__('Parent record not found.'));
            }

            $displayField = $parentTable->getDisplayField();
            $parentContext = [
                'association' => $association->getName(),
                'foreignKey' => $foreignKey,
                'foreignKeyValue' => $filterValue,
                'parentFilter' => $parentFilter,
                'controller' => $association->getName(),
                'displayField' => $displayField,
                'label' => (string)($parentEntity->{$displayField} ?? $filterValue),
            ];
            break;
        }

        $search = trim((string)($queryParams['search'] ?? ''));
        if ($search !== '' && $searchableFields) {
            $searchLike = '%' . $search . '%';
            $conditions = [];
            foreach ($searchableFields as $field) {
                $conditions[$field . ' LIKE'] = $searchLike;
            }
            $query->where(['OR' => $conditions]);
        }

        try {
            $photos = $this->paginate($query);
            if (!empty($queryParams)) {
                $this->session->write('Paging.Photos.params', $queryParams);
            } elseif ($parentContext === null) {
                $this->session->delete('Paging.Photos.params');
            }
        } catch (\Cake\Http\Exception\NotFoundException $e) {
            $this->Flash->warning(__('Page not found. Redirecting to the first page.'), ['plugin' => 'KvAdmin']);
            $fallbackParams = $queryParams;
            unset($fallbackParams['page']);
            $this->session->write('Paging.Photos.params', $fallbackParams);

            return $this->redirect([
                'action' => 'index',
                '?' => $fallbackParams,
            ]);
        }

        $lastViewedId = $this->session->read('LastViewed.photo_id');
        $scrollToId = $this->session->read('ScrollTo.photo_id') ?? $lastViewedId;
        $this->set(compact('photos', 'lastViewedId', 'scrollToId', 'search', 'parentContext'));
    }

    /**
     * View method
     *
     * @param string|null $id Photo id.
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function view($id = null)
    {
        $photo = $this->Photos->get($id, contain: ['PhotoCategories', 'Tags']);
        $this->session->write('LastViewed.Admin.photo_id', (int)$id);
        $this->session->write('ScrollTo.Admin.photo_id', (int)$id);
        $this->set(compact('photo'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $photo = $this->Photos->newEmptyEntity();
        if ($this->request->is('post')) {
            $data = $this->request->getData();
            /** @var \Psr\Http\Message\UploadedFileInterface|null $upload */
            $upload = $data['image_file'] ?? null;
            unset($data['image_file'], $data['uuid'], $data['filename'], $data['tags_count']);

            if (!$upload instanceof UploadedFileInterface || $upload->getError() !== UPLOAD_ERR_OK) {
                $this->Flash->error(__('Please choose an image to upload.'), ['plugin' => 'KvAdmin']);
            } else {
                try {
                    $tmpPath = $upload->getStream()->getMetadata('uri');
                    if (is_string($tmpPath) && is_file($tmpPath)) {
                        $data = $this->photoFiles->mergeExifIntoData($data, $this->photoFiles->extractExif($tmpPath), true);
                    }

                    if (empty($data['slug']) && !empty($data['title'])) {
                        $data['slug'] = Text::slug(mb_strtolower((string)$data['title']), '-');
                    }
                    $data['filename'] = 'pending';
                    $data['code'] = $data['code'] ?? ($data['slug'] ?? null);

                    $photo = $this->Photos->patchEntity($photo, $data);
                    if ($this->Photos->save($photo)) {
                        $relative = $this->photoFiles->storeUploaded($photo, $upload);
                        $photo->filename = $relative;
                        $this->Photos->saveOrFail($photo);

                        $this->Flash->success(__('The {0} has been saved.', __('photo')), ['plugin' => 'KvAdmin']);
                        $this->session->write('ScrollTo.Admin.photo.id', $photo->id ?? 0);

                        return $this->redirect(['action' => 'index']);
                    }
                    $this->Flash->error(__('Could not save data. Please review the errors and try again.'), ['plugin' => 'KvAdmin']);
                } catch (\Throwable $e) {
                    $this->Flash->error($e->getMessage(), ['plugin' => 'KvAdmin']);
                }
            }
        }

        $photoCategories = $this->Photos->PhotoCategories->find('list', limit: 200)->all();
        $tags = $this->Photos->Tags->find('list', limit: 200)->all();
        $this->set(compact('photo', 'photoCategories', 'tags'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Photo id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     */
    public function edit($id = null)
    {
        $photo = $this->Photos->get($id, contain: ['Tags']);
        $this->session->write('LastViewed.Admin.photo_id', (int)$id);
        $this->session->write('ScrollTo.Admin.photo_id', (int)$id);
        $oldFilename = (string)$photo->filename;

        if ($this->request->is(['patch', 'post', 'put'])) {
            $data = $this->request->getData();
            /** @var \Psr\Http\Message\UploadedFileInterface|null $upload */
            $upload = $data['image_file'] ?? null;
            unset($data['image_file'], $data['uuid'], $data['filename'], $data['tags_count']);

            $hasNewFile = $upload instanceof UploadedFileInterface && $upload->getError() === UPLOAD_ERR_OK;
            try {
                if ($hasNewFile) {
                    $tmpPath = $upload->getStream()->getMetadata('uri');
                    if (is_string($tmpPath) && is_file($tmpPath)) {
                        $data = $this->photoFiles->mergeExifIntoData($data, $this->photoFiles->extractExif($tmpPath), true);
                    }
                }

                if (empty($data['slug']) && !empty($data['title'])) {
                    $data['slug'] = Text::slug(mb_strtolower((string)$data['title']), '-');
                }

                $photo = $this->Photos->patchEntity($photo, $data);
                if ($this->Photos->save($photo)) {
                    if ($hasNewFile) {
                        $relative = $this->photoFiles->storeUploaded($photo, $upload, $oldFilename);
                        if ($relative !== $photo->filename) {
                            $photo->filename = $relative;
                            $this->Photos->saveOrFail($photo);
                        }
                    }

                    $this->Flash->success(__('The {0} has been saved.', __('photo')), ['plugin' => 'KvAdmin']);
                    $redirectParams = (array)$this->session->read('Paging.Photos.params');

                    return $this->redirect([
                        'action' => 'index',
                        '?' => $redirectParams,
                    ]);
                }
                $this->Flash->error(__('Could not save data. Please review the errors and try again.'), ['plugin' => 'KvAdmin']);
            } catch (\Throwable $e) {
                $this->Flash->error($e->getMessage(), ['plugin' => 'KvAdmin']);
            }
        }

        $photoCategories = $this->Photos->PhotoCategories->find('list', limit: 200)->all();
        $tags = $this->Photos->Tags->find('list', limit: 200)->all();
        $this->set(compact('photo', 'photoCategories', 'tags'));
    }

    /**
     * AJAX: read EXIF from an uploaded image and return JSON for form autofill.
     *
     * @return \Cake\Http\Response
     */
    public function extractExif()
    {
        $this->request->allowMethod(['post']);
        $this->autoRender = false;

        $upload = $this->request->getData('image_file');
        if (!$upload instanceof UploadedFileInterface || $upload->getError() !== UPLOAD_ERR_OK) {
            return $this->response
                ->withType('application/json')
                ->withStatus(400)
                ->withStringBody(json_encode([
                    'success' => false,
                    'message' => __('Please choose an image to upload.'),
                ]));
        }

        try {
            $this->photoFiles->extensionFromUpload($upload);
            $tmpPath = tempnam(sys_get_temp_dir(), 'exif');
            if ($tmpPath === false) {
                throw new \RuntimeException(__('Could not create upload directory.'));
            }
            $stream = $upload->getStream();
            if ($stream->isSeekable()) {
                $stream->rewind();
            }
            file_put_contents($tmpPath, (string)$stream->getContents());
            $exif = $this->photoFiles->extractExif($tmpPath);
            @unlink($tmpPath);

            return $this->response
                ->withType('application/json')
                ->withStringBody(json_encode([
                    'success' => true,
                    'exif' => $exif,
                ]));
        } catch (\Throwable $e) {
            return $this->response
                ->withType('application/json')
                ->withStatus(422)
                ->withStringBody(json_encode([
                    'success' => false,
                    'message' => $e->getMessage(),
                ]));
        }
    }

    /**
     * Delete method
     *
     * @param string|null $id Photo id.
     * @return \Cake\Http\Response|null Redirects to index.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        $photo = $this->Photos->get($id);
        $this->session->delete('LastViewed.photo_id');

        $neighbor = $this->Photos->find()
            ->select(['id'])
            ->where(['id <' => (int)$id])
            ->orderByDesc('id')
            ->first();
        if (!$neighbor) {
            $neighbor = $this->Photos->find()
                ->select(['id'])
                ->where(['id >' => (int)$id])
                ->orderByAsc('id')
                ->first();
        }
        if ($neighbor) {
            $this->session->write('ScrollTo.photo_id', (int)$neighbor->id);
        } else {
            $this->session->delete('ScrollTo.photo_id');
        }

        if ($this->Photos->delete($photo)) {
            $this->Flash->success(__('The {0} has been successfully deleted.', __('photo')), ['plugin' => 'KvAdmin']);
        } else {
            $this->Flash->error(__('Could not delete the record. Please try again.'), ['plugin' => 'KvAdmin']);
        }

        $redirectParams = (array)$this->session->read('Paging.Photos.params');

        return $this->redirect([
            'action' => 'index',
            '?' => $redirectParams,
        ]);
    }
}
