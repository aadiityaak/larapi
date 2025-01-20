<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\NewOrderNotification;
use App\Models\User;
use App\Models\Jobdesk; // Pastikan Anda mengimpor model Jobdesk
use Illuminate\Support\Facades\Notification;

class SendNotificationController extends Controller
{
  /**
   * Mengirim pengingat jobdesk ke pengguna.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function sendJobdeskReminder(Request $request)
  {
    // Validasi input
    $request->validate([
      'jobdesk_id' => 'required|integer|exists:jobdesks,id',
      'message' => 'nullable|string',
    ]);

    // Ambil jobdesk dan pengguna terkait
    $jobdesk = Jobdesk::find($request->jobdesk_id);
    $user = User::find($jobdesk->user_id);

    // Kirim notifikasi
    Notification::send($user, new NewOrderNotification($request->message));

    return response()->json(['message' => 'Notifikasi pengingat jobdesk berhasil dikirim!']);
  }
}
