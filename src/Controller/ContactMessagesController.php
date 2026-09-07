<?php
declare(strict_types=1);

namespace App\Controller;

/**
 * ContactMessages Controller
 *
 * @property \App\Model\Table\ContactMessagesTable $ContactMessages
 */
class ContactMessagesController extends AppController
{
    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $page = $this->fetchTable('Pages')->getBySlug('kapcsolat');
        $contactMessage = $this->ContactMessages->newEmptyEntity();
        $sent = false;

        if ($this->request->is('post')) {
            $contactMessage = $this->ContactMessages->patchEntity($contactMessage, $this->request->getData());
            if ($this->ContactMessages->save($contactMessage)) {
                $sent = true;
                $contactMessage = $this->ContactMessages->newEmptyEntity();
            } else {
                $this->Flash->error(__('The message could not be sent. Please check the data you entered.'));
            }
        }

        $this->set(compact('page', 'contactMessage', 'sent'));
        $this->set('title', $page->title);
        $this->set('basePagePath', '/kapcsolat');
    }
}
