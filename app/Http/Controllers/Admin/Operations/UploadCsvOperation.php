<?php
namespace App\Http\Controllers\Admin\Operations;

use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\Advertisement;

trait UploadCsvOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular).
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupUploadCsvRoutes($segment, $routeName, $controller): void
    {
        Route::get($segment.'/upload-csv', [
            'as'        => $routeName.'.uploadCsvForm',
            'uses'      => $controller.'@showUploadForm',
            'operation' => 'uploadCsv',
        ]);

        Route::post($segment.'/upload-csv', [
            'as'        => $routeName.'.uploadCsv',
            'uses'      => $controller.'@uploadCsv',
            'operation' => 'uploadCsv',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupUploadCsvDefaults(): void
    {
        CRUD::allowAccess('uploadCsv');

        CRUD::operation('uploadCsv', function () {
            CRUD::addButton('top', 'uploadCsv', 'view', 'crud::buttons.upload_csv');
        });
    }

    /**
     * Show the CSV upload form.
     */
    public function showUploadForm(): \Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        return view('advertisements.upload_csv');
    }

    /**
     * Handle the CSV upload.
     */
    public function uploadCsv(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'csv_file' => 'required|mimes:csv,txt',
        ]);

        $path = $request->file('csv_file')->getRealPath();
        $data = array_map('str_getcsv', file($path));
        $header = array_shift($data);

        foreach ($data as $row) {
            $row = array_combine($header, $row);
            Advertisement::create([
                'title' => $row['title'],
                'description' => $row['description'],
                'price' => $row['price'],
            ]);
        }

        return redirect()->route('crud.advertisement.index')->with('success', 'Advertisements uploaded successfully.');
    }
}
