<?php
declare(strict_types=1);

namespace App\Controller\Admin;

/**
 * Setup – site-level toggles (maintenance, etc.).
 */
class SetupController extends AppController
{
    /**
     * Maintenance and other site switches.
     *
     * @return \Cake\Http\Response|null|void
     */
    public function index()
    {
        $settings = $this->fetchTable('Settings');

        if ($this->request->is(['post', 'put', 'patch'])) {
            $enabled = (bool)$this->request->getData('maintenance_mode');
            $message = trim((string)$this->request->getData('maintenance_message'));
            if ($message === '') {
                $message = 'Az oldal jelenleg karbantartás alatt áll.';
            }

            $settings->setValue(
                'maintenance_mode',
                $enabled ? '1' : '0',
                'Karbantartás mód',
                90,
            );
            $settings->setValue(
                'maintenance_message',
                $message,
                'Karbantartás üzenet',
                91,
            );

            $this->Flash->success(__('A beállítások mentve.'));

            return $this->redirect(['action' => 'index']);
        }

        $maintenanceMode = $settings->isEnabled('maintenance_mode');
        $maintenanceMessage = (string)$settings->getValue(
            'maintenance_message',
            'Az oldal jelenleg karbantartás alatt áll.',
        );

        $this->set(compact('maintenanceMode', 'maintenanceMessage'));
        $this->set('title', __('Setup'));
    }
}
