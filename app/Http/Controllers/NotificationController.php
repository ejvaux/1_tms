<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notifications\TicketAssigned;
use App\Notifications\TicketAccepted;
use App\Notifications\PriorityChanged;
use App\Notifications\StatusChanged;
use App\Notifications\TicketClosed;
use App\Notifications\TicketCreated;
use Auth;
use App\User;

class NotificationController extends Controller
{    
    public function markallread(){
        Auth::user()->unreadNotifications->markAsRead();
        return redirect()->back();
    }
    public function clearnotification(){
        Auth::user()->notifications()->delete();
        return redirect()->back();
    }
    public function markread($id,$mod,$tid){
        Auth::user()->notifications->where('id',$id)->first()->markAsRead();
        if($mod == 'user'){
            return redirect(url('/it/vt/'.$tid));
        }
        else if($mod == 'assign_admin'){
            return redirect(url('/it/htv/'.$tid));
        }
        else if($mod == 'create'){
            return redirect(url('/it/av/'.$tid));
        }        
        else if($mod == 'close'){
            return redirect(url('/it/ctlv/'.$tid));
        }
    }
    public function ticketcreate($tid,$mod){
        $users = User::where('admin',1)->get();
        foreach ($users as $user) {
            $user->notify(new TicketCreated($tid,$user->name));
        }
        if($mod == 'default'){
            return redirect('/it/ct')->with('success','Ticket Submitted Successfully.');
        }else if($mod == 'admin'){
            return redirect('/it/ac')->with('success','Ticket Submitted Successfully.');
        }
    }
    public function ticketassign($id,$tid,$tech){
        $user = User::where('id',$id)->first();
        $tech = User::where('id',$tech)->first();
        $user->notify(new TicketAssigned($tid,$user->name,'user'));
        $tech->notify(new TicketAssigned($tid,$tech->name,'tech'));
        return redirect('/it/av/'.$tid)->with('success','Ticket Assigned Successfully.'); 
    }
    public function ticketaccept($id,$tid,$tech){
        $user = User::where('id',$id)->first();
        $user->notify(new TicketAccepted($tid,$user->name,$tech));
        return redirect('/it/htv/'.$tid)->with('success','Ticket Accepted Successfully.'); 
    }
    public function ticketpriority($id,$tid,$prio){
        $user = User::where('id',$id)->first();
        $user->notify(new PriorityChanged($tid,$user->name,$prio));
        return redirect('/it/htv/'.$tid)->with('success','Priority Changed Successfully.');
    }
    public function ticketstatus($id,$tid,$stat){
        $user = User::where('id',$id)->first();
        $user->notify(new StatusChanged($tid,$user->name,$stat));
        return redirect('/it/htv/'.$tid)->with('success','Status Changed Successfully.');
    }
    public function ticketclose($id,$tid){
        $user = User::where('id',$id)->first();
        $user->notify(new TicketClosed($tid,$user->name));
        return redirect('/it/ht')->with('success','Ticket Closed Successfully.');
    }    
}
