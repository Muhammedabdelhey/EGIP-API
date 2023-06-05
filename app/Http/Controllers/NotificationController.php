<?php

namespace App\Http\Controllers;

use App\Events\SendNotification;
use App\Models\Patient;
use App\Notifications\Raspberrypi;
use App\Repositories\Interfaces\CaregiverRepositoryInterface;
use App\Repositories\Interfaces\PatientRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class NotificationController extends Controller
{
    public function __construct(
        public PatientRepositoryInterface $patientRepository,
        private CaregiverRepositoryInterface $caregiverRepository
    ) {
    }

    public function addNotify(Request $request)
    {
        $patient_id = $request->patient_id;
        $patient = $this->patientRepository->getPatient($patient_id);
        $caregivers = $patient->caregivers;
        foreach ($caregivers as $caregiver) {
            $caregiver_ids[] = $caregiver->id;
        }
        print($caregiver_ids);
        die;
        Notification::send($patient, new Raspberrypi($patient_id, $request->message));
        Notification::send($caregivers, new Raspberrypi($patient_id, $request->message));
        event(new SendNotification((int)$patient_id, $caregivers->id, $request->message));
        return 'done';
    }

    public function getPaientNotifications($patient_id)
    {
        $patient = $this->patientRepository->getPatient($patient_id);
        $notifications = [];
        foreach ($patient->notifications as $notification) {
            $notifications[] = ['message' => $notification->data['message'], 'time' => $notification->created_at->diffForHumans()];
        }
        return responseJson(200, $notifications, 'done');
    }

    public function getCaregiverNotifications($caregiver_id)
    {
        $caregiver = $this->caregiverRepository->getCaregiver($caregiver_id);
        $notifications = [];
        foreach ($caregiver->notifications as $notification) {
            $patient = $this->patientRepository->getPatient($notification->data['patient_id']);
            $notifications[] = [
                'patient' => $patient->user->name,
                'message' => $notification->data['message'],
                'time' => $notification->created_at->diffForHumans()
            ];
        }
        return responseJson(200, $notifications, 'done');
    }
}
