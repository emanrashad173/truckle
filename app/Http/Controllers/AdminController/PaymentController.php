<?php

namespace App\Http\Controllers\AdminController;

use Illuminate\Http\Request;
use App\Order;
use App\UserDevice;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }
    public function new()
    {
        $payments = Order::where('state','completed')->where('paid','=','0')->orderBy('created_at' , 'desc')->get();
        return view('admin.payments.new' , compact('payments'));
    }
    public function paid()
    {
        $payments = Order::where('state','completed')->where('paid','=','1')->orderBy('created_at' , 'desc')->get();
        return view('admin.payments.paid' , compact('payments'));
    }
    public function confirmed()
    {
        $payments = Order::where('state','completed')->where('paid','=','2')->orderBy('created_at' , 'desc')->get();
        return view('admin.payments.confirmed' , compact('payments'));
    }

    public function updateStatus($id)
    {
        $bank = Order::find($id);
        return view('admin.payments.update', compact('bank'));
    }

    public function postUpdateStatus(Request $request, $id)
    {
        $this->validate($request, [
            'paid' => 'required'
        ]);
        $payment = Order::findOrFail($id);
        $payment->update([
            'paid' => $request->paid
        ]);
        $userId = $payment->driver_id;
        $ar_title = "طلب الدفع";
        $en_title = "pay Request" ;
        $hi_title = "वेतन आयोग" ;
        $type = 1;
         $ar_message = "تم التأكيد علي طلب الدفع   " ;
        $en_message = "Payment request has been confirmed" ;
        $hi_message = "वेतन आयोग की पुष्टि की गई है" ;
        $devicesTokens = UserDevice::where('user_id', $id)
            ->get()
            ->pluck('device_token')
            ->toArray();
        if ($devicesTokens) {
            sendMultiNotification("طلب الدفع", "تم التأكيد علي طلب الدفع   " . $payment->id, $devicesTokens);
        }
        saveNotification($userId,$ar_title,$en_title,$hi_title,$type,$ar_message,$en_message,$hi_message,$payment->id);
        // dd($order);
        flash('تم تعديل حالة الدفع بنجاح');
        return back();
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $payment = Order::findOrFail($id);
        $payment->delete();
        flash('تم حذف بيانات  طلب الدفع   بنجاح')->success();
        return back();
    }
}
