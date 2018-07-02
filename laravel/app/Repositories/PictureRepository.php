<?php

namespace App\Repositories;

use Exception;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;

class PictureRepository
{
    //upload picture
    public function uploadPicture($picture, $directory)
    {
        try
        {
            //set picture path
            $picture_path = storage_path().'/app/public/'.$directory;

            //generate picture string
            $picture_string = substr(md5(rand()), 0, 30);

            //get picture extension
            $extension = $picture->getClientOriginalExtension();

            //set picture name
            $picture_name = $picture_string.'.'.$extension;

            //upload picture
            $picture->move($picture_path, $picture_name);

            return ['status' => 1, 'data' => $picture_name];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //delete picture
    public function deletePicture($name, $directory)
    {
        try
        {
            //set picture path
            $picture_path = storage_path().'/app/public/'.$directory.'/'.$name;

            //delete picture
            File::delete($picture_path);

            return ['status' => 1];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }

    //upload photo
    public function uploadPhoto($photo)
    {
        try
        {
            $photo = str_replace('data:image/jpeg;base64,', '', $photo);

            //generate photo string
            $photo_string = substr(md5(rand()), 0, 30);

            //set photo path
            $photo_path = storage_path().'/app/public/photos/'.$photo_string.'.jpg';

            //decode photo
            $photo = Image::make(base64_decode($photo));

            //save photo
            $photo->save($photo_path);

            //set photo name
            $photo_name = $photo_string.'.jpg';

            return ['status' => 1, 'data' => $photo_name];
        }
        catch (Exception $e)
        {
            return ['status' => 0];
        }
    }
}
