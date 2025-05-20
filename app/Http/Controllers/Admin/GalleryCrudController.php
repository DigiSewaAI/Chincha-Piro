<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class GalleryCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class GalleryCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Gallery::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/gallery');
        CRUD::setEntityNameStrings('gallery', 'galleries');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        // Manual column definitions with Nepali labels
        $this->crud->addColumn([
            'name' => 'title',
            'label' => 'शीर्षक',
            'type' => 'text'
        ]);

        $this->crud->addColumn([
            'name' => 'type',
            'label' => 'प्रकार',
            'type' => 'enum'
        ]);

        // Optional: Add this if you want to ensure responsive table behavior
        // $this->crud->disableResponsiveTable();
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation([
            // Add validation rules if needed, e.g., 'title' => 'required|min:2',
        ]);

        // Manual field definitions with Nepali labels and upload support
        $this->crud->addField([
            'name' => 'title',
            'label' => 'शीर्षक',
            'type' => 'text'
        ]);

        $this->crud->addField([
            'name' => 'type',
            'label' => 'प्रकार',
            'type' => 'enum',
            'options' => ['photo' => 'फोटो', 'video' => 'भिडियो']
        ]);

        $this->crud->addField([
            'name' => 'file_path',
            'label' => 'फाइल',
            'type' => 'upload',
            'upload' => true,
            // Optional: Add disk/path configuration if needed
            // 'disk' => 'public',
            // 'prefix' => 'uploads/'
        ]);
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();

        // Optional: Adjust fields for update if needed (e.g., make file_path optional)
        // $this->crud->modifyField('file_path', ['required' => false]);
    }
}
