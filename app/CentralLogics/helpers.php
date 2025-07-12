<?php


namespace App\CentralLogics;
use Illuminate\Support\Facades\File;

class Helpers {
    
    public static function storeImage($request, $model, $instance, $parameter, $module, $field = 'images', $extraFields = [])
    {
        if ($request->hasFile($field)) {

            // Delete old images
            $existingImages = $model::where($parameter, $instance->id)->get();
            foreach ($existingImages as $exImage) {
                if (!empty($exImage->image)) {
                    self::mmadev_delete_image_attachment_from_directory($exImage->image, $module);
                }
            }

            // Delete DB records
            $model::where($parameter, $instance->id)->delete();

            // Save new images
            foreach ($request->file($field) as $file) {
                $imagePath = self::mmadev_store_and_get_image_path($module, $file);

                $data = array_merge([
                    $parameter => $instance->id,
                    'image' => $imagePath,
                ], $extraFields);

                $model::create($data);
            }
        }
    }
    
    public static function mmadev_delete_image_attachment_from_directory($file, $module)
    {
        // $upload_dir = 'imgs/' . $module . '/';
        $upload_dir = public_path("imgs/".$module."/".$file);
        // delete from directory
        if (File::exists($upload_dir)) {
            // Delete from the directory
            File::delete($upload_dir);
        }
    }


    public static function mmadev_store_and_get_image_path($module, $file)
    {
        $image_path = str_replace(' ', '', $file->getClientOriginalName());

        $unique_id  = uniqid();
        $image_path = $unique_id . '-' . $image_path;
        $timestamp  = now()->format('d_m_Y_His');
        $image_path = "{$timestamp}_{$image_path}";

        $uploads_directory = self::mmadev_get_uploads_directory_monthly($module);

        $file->move($uploads_directory, $image_path);

        return $image_path;
    }

    public static function mmadev_get_uploads_directory_monthly($module = '')
    {
        $uploads_dir = public_path('/imgs/' . $module . '/');

        // create monthly directory if not exists
        if (!file_exists($uploads_dir)) {
            mkdir($uploads_dir, 0777, true);
        }

        return $uploads_dir;
    }
}