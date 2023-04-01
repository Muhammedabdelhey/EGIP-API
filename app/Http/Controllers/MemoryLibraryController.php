<?php

namespace App\Http\Controllers;

use App\Http\Requests\MemoryRequest;
use App\Models\MemoryLibrary;
use App\Models\Patient;
use App\Traits\ManageFileTrait;
use Carbon\Carbon;

class MemoryLibraryController extends Controller
{
    use ManageFileTrait;
    public function addMemory(MemoryRequest $request)
    {
        $photo = $this->uploadFile($request, 'photo', 'memoriesPhotos');
        $memory = MemoryLibrary::create([
            'name' => $request->name,
            'description' => $request->description,
            'photo' => $photo,
            'type' => $request->type,
            'patient_id' => $request->patient_id
        ]);
        $data = MemoryData($memory);
        return responseJson(201, $data, "memory Insertes ");
    }

    public function getMemory($memory_id)
    {
        $memory = MemoryLibrary::find($memory_id);
        if ($memory) {
            $data = MemoryData($memory);
            return responseJson(201, $data, "memory data");
        } // test
        return responseJson(401, '', 'this memory_id not found');
    }
    public function getMemories($patient_id)
    {
        $patient = Patient::find($patient_id);
        if ($patient) {
            if ($patient->memories->count() > 0) {
                $memories = $patient->memories;
                foreach ($memories as $memory) {
                    $data[] = MemoryData($memory);
                }
                return responseJson(201, $data, 'memories data');
            }
            return responseJson(401, '', 'this Patient Not have Any Memories');
        }
        return responseJson(401, '', 'this patient_id not found');
    }
    public function deleteMemory($memory_id)
    {
        $memory = MemoryLibrary::find($memory_id);
        if ($memory) {
            $this->deleteFile($memory->photo);
            MemoryLibrary::destroy($memory_id);
            return responseJson(201, '', ' Memory Deleted');
        }
        return responseJson(401, '', 'this memory_id not found');
    }

    public function updateMemory(MemoryRequest $request, $id)
    {
        $memory = MemoryLibrary::find($id);
        if ($memory) {
            $photo = $this->uploadFile($request, 'photo', 'memoriesPhotos');
            if (!empty($photo)) {
                $this->deleteFile($memory->photo);
            } else {
                $photo = $memory->photo;
            }
            $memory->update([
                'name' => $request->name,
                'description' => $request->description,
                'type' => $request->type,
                'photo' => $photo,
                'updated_at' => Carbon::now()
            ]);
            $data = MemoryData($memory);
            return responseJson(201, $data, 'Memory Updated');
        }
        return responseJson(401, '', 'this memory_id not found');
    }
    public function getMemoryImage($memory_id)
    {
        $memory = MemoryLibrary::find($memory_id);
        return $this->getFile($memory->photo);
    }
   
}
