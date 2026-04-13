<?php



namespace App\Http\Controllers\Api;



use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\UserMining;

use Carbon\Carbon;

use App\Models\Coin;

use Illuminate\Support\Facades\Hash;

use App\Models\Country;

use Illuminate\Support\Facades\Auth;

use DB;

class MiningController extends Controller

{



// public function activate(Request $request)

// {

//     $user = auth()->user();



//     if(!$user){

//         return response()->json([

//             'message'=>'Unauthenticated'

//         ],401);

//     }



//     $mining = UserMining::where('user_id',$user->id)->first();



//     if(!$mining){

//         return response()->json([

//             'message'=>'Mining record not found'

//         ],404);

//     }



//     // $monthlyProgress = 100/24;

// // $totalMonths = $this->getCoinDuration($mining->coin_type);
// // $monthlyProgress = 100 / $totalMonths;

// $coin = Coin::where('name', $mining->coin_type)->first();

// // $totalMonths = $coin ? $coin->mining_period : 24;
// // $monthlyProgress = 100 / $totalMonths;
// $totalMonths = (int) ($coin ? $coin->mining_period : 24);
// $inactiveMonths = (int) $mining->inactive_months;

//     /*

//     FIRST TIME ACTIVATE

//     */



//     if(!$mining->start_date){



//         $start = now();



//         $mining->update([



//             'start_date'=>$start,



//             'monthly_due_date'=>$start->copy()->addMonth(),



//             // 'end_date'=>$start->copy()->addMonths(24),

// // 'end_date'=>$start->copy()->addMonths($totalMonths),
// 'end_date'=>$start->copy()->addMonths((int)$totalMonths),

//             'is_active'=>true,



//             'last_activated_at'=>now(),



//             'progress'=>0

//         ]);



//         return response()->json([

//             'message'=>'Mining started successfully',

//             'progress'=>0

//         ]);

//     }



//     /*

//     EARLY ACTIVATION (Before due date)

//     */



//     // if(now()->lt($mining->monthly_due_date)){



//     //     $mining->update([

//     //         'pending_activation'=>true,

//     //         'next_cycle_activated_at' => now()

//     //     ]);



//     //     return response()->json([

//     //         'message'=>'Activation stored for next cycle'

//     //     ]);

//     // }



//      if(now()->lt($mining->monthly_due_date)){



//         if($mining->next_cycle_activated_at){

//             return response()->json([

//                 'message'=>'Next cycle already activated'

//             ]);

//         }



//         $mining->update([

//             'pending_activation'=>true,

//             'next_cycle_activated_at'=>now()

//         ]);



//         return response()->json([

//             'message'=>'Activation stored for next cycle'

//         ]);

//     }



//     /*

//     MONTH COMPLETED

//     */



//     if(now()->gte($mining->monthly_due_date)){



//         if($mining->pending_activation){



//             $progress = $mining->progress + $monthlyProgress;



//         }else{



//             // user missed activation

//             $mining->inactive_months += 1;



//             $progress = $mining->progress;

//         }



//         $nextDue = Carbon::parse($mining->monthly_due_date)->addMonth();



//         /*

//         END DATE EXTEND BASED ON INACTIVE MONTHS

//         */



//         // $endDate = Carbon::parse($mining->start_date)

//         //             ->addMonths(24 + $mining->inactive_months);
// $endDate = Carbon::parse($mining->start_date)
//             ->addMonths($totalMonths + $mining->inactive_months);


//         $mining->update([



//             'progress'=>min(100,$progress),



//             'monthly_due_date'=>$nextDue,



//             'pending_activation'=>false,



//             'last_activated_at'=>now(),



//             'end_date'=>$endDate

//         ]);



//         return response()->json([



//             'message'=>'Mining updated',



//             'progress'=>round($progress,2),



//             'inactive_months'=>$mining->inactive_months,



//             'new_end_date'=>$endDate



//         ]);

//     }

// }



// sowmiya

// public function activate(Request $request)
// {
//     $user = auth()->user();
//     if(!$user){
//         return response()->json([
//             'message'=>'Unauthenticated'
//         ],401);
//     }

//     $mining = UserMining::where('user_id',$user->id)->first();
//     if(!$mining){
//         return response()->json([
//             'message'=>'Mining record not found'
//         ],404);
//     }

//     $coin = Coin::where('name', $mining->coin_type)->first();
//     $totalMonths = (int) ($coin ? $coin->mining_period : 24);
//     $inactiveMonths = (int) $mining->inactive_months;
//     $monthlyProgress = 100 / $totalMonths; // ✅ moved here so all blocks can use it

//     /*
//     FIRST TIME ACTIVATE
//     */
//     if(!$mining->start_date){
//         $start = now();

//         $firstProgress = round((1 / $totalMonths) * 100, 2); // ✅ FIX

//         $mining->update([
//             'start_date'        => $start,
//             'monthly_due_date'  => $start->copy()->addMonth(),
//             'end_date'          => $start->copy()->addMonths((int)$totalMonths),
//             'is_active'         => true,
//             'last_activated_at' => now(),
//             'progress'          => $firstProgress, // ✅ was 0
//         ]);

//         return response()->json([
//             'message'  => 'Mining started successfully',
//             'progress' => $firstProgress, // ✅ was 0
//         ]);
//     }

//     /*
//     EARLY ACTIVATION (Before due date)
//     */
//     if(now()->lt($mining->monthly_due_date)){
//         if($mining->next_cycle_activated_at){
//             return response()->json([
//                 'message'=>'Next cycle already activated'
//             ]);
//         }
//         $mining->update([
//             'pending_activation'      => true,
//             'next_cycle_activated_at' => now()
//         ]);
//         return response()->json([
//             'message'=>'Activation stored for next cycle'
//         ]);
//     }

//     /*
//     MONTH COMPLETED
//     */
//     if(now()->gte($mining->monthly_due_date)){
//         if($mining->pending_activation){
//             $progress = $mining->progress + $monthlyProgress;
//         }else{
//             // user missed activation
//             $mining->inactive_months += 1;
            
// //             $missedMonths = Carbon::parse($mining->monthly_due_date)
// //     ->diffInMonths(now());

// // $mining->inactive_months += $missedMonths;
//             $progress = $mining->progress;
//         }

//         $nextDue = Carbon::parse($mining->monthly_due_date)->addMonth();
// // $nextDue = Carbon::parse($mining->monthly_due_date)
// //     ->addMonths($missedMonths ?? 1);
//         /*
//         END DATE EXTEND BASED ON INACTIVE MONTHS
//         */
//         $endDate = Carbon::parse($mining->start_date)
//                     ->addMonths($totalMonths + $mining->inactive_months);

//         $mining->update([
//             'progress'          => min(100, $progress),
//             'monthly_due_date'  => $nextDue,
//             'pending_activation'=> false,
//             'last_activated_at' => now(),
//             'end_date'          => $endDate
//         ]);

//         return response()->json([
//             'message'        => 'Mining updated',
//             'progress'       => round($progress, 2),
//             'inactive_months'=> $mining->inactive_months,
//             'new_end_date'   => $endDate
//         ]);
//     }
// }


// 01-04


// public function activate(Request $request)
// {
//     $user = auth()->user();
//     if(!$user){
//         return response()->json([
//             'message'=>'Unauthenticated'
//         ],401);
//     }
//     $mining = UserMining::where('user_id',$user->id)->first();
//     if(!$mining){
//         return response()->json([
//             'message'=>'Mining record not found'
//         ],404);
//     }
//     $coin = Coin::where('name', $mining->coin_type)->first();
//     $totalMonths = (int) ($coin ? $coin->mining_period : 24);
//     $inactiveMonths = (int) $mining->inactive_months;
//     $monthlyProgress = 100 / $totalMonths;

//     /*
//     FIRST TIME ACTIVATE
//     */
//     if(!$mining->start_date){
//         $start = now();
//         $firstProgress = round((1 / $totalMonths) * 100, 2);
//         $mining->update([
//             'start_date'        => $start,
//             'monthly_due_date'  => $start->copy()->addMonth(),
//             'end_date'          => $start->copy()->addMonths((int)$totalMonths),
//             'is_active'         => true,
//             'last_activated_at' => now(),
//             'progress'          => $firstProgress,
//         ]);

//         DB::table('user_mining_history')->insert([
//             'user_id'          => $user->id,
//             'coin_type'        => $mining->coin_type,
//             'start_date'       => $start,
//             'end_date'         => $start->copy()->addMonths((int)$totalMonths),
//             'monthly_due_date' => $start->copy()->addMonth(),
//             'created_at'       => now(),
//             'updated_at'       => now(),
//         ]);

//         return response()->json([
//             'message'  => 'Mining started successfully',
//             'progress' => $firstProgress,
//         ]);
//     }

//     /*
//     EARLY ACTIVATION (Before due date)
//     */
//     if(now()->lt($mining->monthly_due_date)){
//         if($mining->next_cycle_activated_at){
//             return response()->json([
//                 'message'=>'Next cycle already activated'
//             ]);
//         }
//         $mining->update([
//             'pending_activation'      => true,
//             'next_cycle_activated_at' => now()
//         ]);

//         DB::table('user_mining_history')->insert([
//             'user_id'          => $user->id,
//             'coin_type'        => $mining->coin_type,
//             'start_date'       => $mining->start_date,
//             'end_date'         => $mining->end_date,
//             'monthly_due_date' => $mining->monthly_due_date,
//             'created_at'       => now(),
//             'updated_at'       => now(),
//         ]);

//         return response()->json([
//             'message'=>'Activation stored for next cycle'
//         ]);
//     }

//     /*
//     MONTH COMPLETED
//     */
//     if(now()->gte($mining->monthly_due_date)){
//         if($mining->pending_activation){
//             $progress = $mining->progress + $monthlyProgress;
//         }else{
//             $mining->inactive_months += 1;
//             $progress = $mining->progress;
//         }
//         $nextDue = Carbon::parse($mining->monthly_due_date)->addMonth();

//         /*
//         END DATE EXTEND BASED ON INACTIVE MONTHS
//         */
//         $endDate = Carbon::parse($mining->start_date)
//                     ->addMonths($totalMonths + $mining->inactive_months);
//         $mining->update([
//             'progress'          => min(100, $progress),
//             'monthly_due_date'  => $nextDue,
//             'pending_activation'=> false,
//             'last_activated_at' => now(),
//             'end_date'          => $endDate
//         ]);

//         // DB::table('user_mining_history')->insert([
//         //     'user_id'          => $user->id,
//         //     'coin_type'        => $mining->coin_type,
//         //     'start_date'       => $mining->start_date,
//         //     'end_date'         => $endDate,
//         //     'monthly_due_date' => $nextDue,
//         //     'created_at'       => now(),
//         //     'updated_at'       => now(),
//         // ]);
        
        
        
        
//         DB::table('user_mining_history')->insert([
//     'user_id'          => $user->id,
//     'coin_type'        => $mining->coin_type,
//     'start_date'       => now(), // ✅ actual activate பண்ண date
//     'end_date'         => $mining->end_date,
//     'monthly_due_date' => $mining->monthly_due_date,
//     'created_at'       => now(),
//     'updated_at'       => now(),
// ]);
        
        
        
        
        

//         return response()->json([
//             'message'        => 'Mining updated',
//             'progress'       => round($progress, 2),
//             'inactive_months'=> $mining->inactive_months,
//             'new_end_date'   => $endDate
//         ]);
//     }
// }


// public function activate(Request $request)
// {
//     $user = auth()->user();
//     if (!$user) {
//         return response()->json(['message' => 'Unauthenticated'], 401);
//     }

//     $mining = UserMining::where('user_id', $user->id)->first();
//     if (!$mining) {
//         return response()->json(['message' => 'Mining record not found'], 404);
//     }

//     $coin        = Coin::where('name', $mining->coin_type)->first();
//     $totalMonths = (int)($coin ? $coin->mining_period : 24);

//     // ✅ Remaining progress & months calculate
//     $currentProgress   = (float) $mining->progress;
//     $remainingProgress = 100 - $currentProgress;

//     // Remaining days from now to end_date
//     $endDate         = Carbon::parse($mining->end_date);
//     $remainingDays   = max(now()->diffInDays($endDate, false), 30);
//     $remainingMonths = max(1, round($remainingDays / 30));

//     // ✅ Each month = remaining% / remaining months
//     $monthlyProgress = $remainingProgress / $remainingMonths;

//     /*
//     FIRST TIME ACTIVATE
//     */
//     if (!$mining->start_date) {
//         $start         = now();
//         $firstProgress = round((1 / $totalMonths) * 100, 2);
//         $nextDue       = $start->copy()->addDays(30);
//         $endDate       = $start->copy()->addDays($totalMonths * 30);

//         $mining->update([
//             'start_date'        => $start,
//             'monthly_due_date'  => $nextDue,
//             'end_date'          => $endDate,
//             'is_active'         => true,
//             'last_activated_at' => now(),
//             'progress'          => $firstProgress,
//         ]);

//         DB::table('user_mining_history')->insert([
//             'user_id'          => $user->id,
//             'coin_type'        => $mining->coin_type,
//             'start_date'       => $start,
//             'end_date'         => $endDate,
//             'monthly_due_date' => $nextDue,
//             'created_at'       => now(),
//             'updated_at'       => now(),
//         ]);

//         return response()->json([
//             'message'  => 'Mining started successfully',
//             'progress' => $firstProgress,
//         ]);
//     }

//     /*
//     EARLY ACTIVATION (Before 30 days)
//     */
//     if (now()->lt($mining->monthly_due_date)) {
//         if ($mining->next_cycle_activated_at) {
//             return response()->json(['message' => 'Next cycle already activated']);
//         }

//         $mining->update([
//             'pending_activation'      => true,
//             'next_cycle_activated_at' => now(),
//         ]);

//         DB::table('user_mining_history')->insert([
//             'user_id'          => $user->id,
//             'coin_type'        => $mining->coin_type,
//             'start_date'       => $mining->start_date,
//             'end_date'         => $mining->end_date,
//             'monthly_due_date' => $mining->monthly_due_date,
//             'created_at'       => now(),
//             'updated_at'       => now(),
//         ]);

//         return response()->json(['message' => 'Activation stored for next cycle']);
//     }

//     /*
//     MONTH COMPLETED (30 days passed)
//     */
//     if (now()->gte($mining->monthly_due_date)) {
//         if ($mining->pending_activation) {
//             // ✅ Add monthly progress based on remaining
//             $newProgress = min(100, $currentProgress + $monthlyProgress);
//         } else {
//             // Inactive month - no progress
//             $mining->inactive_months += 1;
//             $newProgress = $currentProgress;
//         }

//         $nextDue = Carbon::parse($mining->monthly_due_date)->addDays(30);

//         // End date extend for inactive months only
//         $totalDays = ($totalMonths + $mining->inactive_months) * 30;
//         $newEndDate = Carbon::parse($mining->start_date)->addDays($totalDays);

//         $mining->update([
//             'progress'           => round($newProgress, 2),
//             'monthly_due_date'   => $nextDue,
//             'pending_activation' => false,
//             'last_activated_at'  => now(),
//             'end_date'           => $newEndDate,
//         ]);

//         DB::table('user_mining_history')->insert([
//             'user_id'          => $user->id,
//             'coin_type'        => $mining->coin_type,
//             'start_date'       => now(),
//             'end_date'         => $newEndDate,
//             'monthly_due_date' => $nextDue,
//             'created_at'       => now(),
//             'updated_at'       => now(),
//         ]);

//         return response()->json([
//             'message'         => 'Mining updated',
//             'progress'        => round($newProgress, 2),
//             'inactive_months' => $mining->inactive_months,
//             'new_end_date'    => $newEndDate,
//         ]);
//     }
// }



// public function activate(Request $request)
// {
//     $user = auth()->user();
//     if (!$user) {
//         return response()->json(['message' => 'Unauthenticated'], 401);
//     }

//     $mining = UserMining::where('user_id', $user->id)->first();
//     if (!$mining) {
//         return response()->json(['message' => 'Mining record not found'], 404);
//     }

//     $coin        = Coin::where('name', $mining->coin_type)->first();
//     $totalMonths = (int)($coin ? $coin->mining_period : 24);

//     $currentProgress   = (float) $mining->progress;
//     $remainingProgress = 100 - $currentProgress;

//     /*
//     FIRST TIME ACTIVATE
//     */
//     if (!$mining->start_date) {
//         $start         = now();
//         $firstProgress = round((1 / $totalMonths) * 100, 2);
//         $nextDue       = $start->copy()->addDays(30);
//         $endDate       = $start->copy()->addDays($totalMonths * 30);

//         $mining->update([
//             'start_date'        => $start,
//             'monthly_due_date'  => $nextDue,
//             'end_date'          => $endDate,
//             'is_active'         => true,
//             'last_activated_at' => now(),
//             'progress'          => $firstProgress,
//             'current_month'     => 1,
//         ]);

//         DB::table('user_mining_history')->insert([
//             'user_id'          => $user->id,
//             'coin_type'        => $mining->coin_type,
//             'start_date'       => $start,
//             'end_date'         => $endDate,
//             'monthly_due_date' => $nextDue,
//             'created_at'       => now(),
//             'updated_at'       => now(),
//         ]);

//         return response()->json([
//             'message'  => 'Mining started successfully',
//             'progress' => $firstProgress,
//         ]);
//     }

//     /*
//     EARLY ACTIVATION (Before 30 days)
//     */
//     if (now()->lt($mining->monthly_due_date)) {
//         if ($mining->next_cycle_activated_at) {
//             return response()->json(['message' => 'Next cycle already activated']);
//         }

//         $mining->update([
//             'pending_activation'      => true,
//             'next_cycle_activated_at' => now(),
//         ]);

//         return response()->json(['message' => 'Activation stored for next cycle']);
//     }

//     /*
//     MONTH COMPLETED (30 days passed)
//     */
//     if (now()->gte($mining->monthly_due_date)) {

//         // ✅ Missed months calculate
//         $monthsSinceStart = (int)(
//             Carbon::parse($mining->start_date)
//                 ->diffInDays(Carbon::parse($mining->monthly_due_date)) / 30
//         );

//         $missedMonths = ($monthsSinceStart + 1) - $mining->current_month;

//         // ✅ End date extend for missed months
//         if ($missedMonths > 0) {
//             $newEndDate              = Carbon::parse($mining->end_date)
//                                         ->addDays($missedMonths * 30);
//             $mining->inactive_months += $missedMonths;
//         } else {
//             $newEndDate = Carbon::parse($mining->end_date);
//         }

//         $nextDue         = Carbon::parse($mining->monthly_due_date)->addDays(30);
//         $remainingMonths = max(1, $totalMonths - $mining->current_month);
//         $monthlyProgress = $remainingProgress / $remainingMonths;
//         $newProgress     = min(100, $currentProgress + $monthlyProgress);

//         $mining->update([
//             'progress'                => round($newProgress, 2),
//             'monthly_due_date'        => $nextDue,
//             'pending_activation'      => false,
//             'next_cycle_activated_at' => null,
//             'last_activated_at'       => now(),
//             'end_date'                => $newEndDate,
//             'current_month'           => $mining->current_month + 1,
//             'inactive_months'         => $mining->inactive_months,
//         ]);

//         // ✅ Always insert history when activate
//         DB::table('user_mining_history')->insert([
//             'user_id'          => $user->id,
//             'coin_type'        => $mining->coin_type,
//             'start_date'       => now(),
//             'end_date'         => $newEndDate,
//             'monthly_due_date' => $nextDue,
//             'created_at'       => now(),
//             'updated_at'       => now(),
//         ]);

//         return response()->json([
//             'message'       => 'Mining updated',
//             'progress'      => round($newProgress, 2),
//             'end_date'      => $newEndDate,
//             'missed_months' => max(0, $missedMonths),
//         ]);
//     }
// }









// public function activate(Request $request)
// {
//     $user = auth()->user();
//     if (!$user) {
//         return response()->json(['message' => 'Unauthenticated'], 401);
//     }

//     $mining = UserMining::where('user_id', $user->id)->first();
//     if (!$mining) {
//         return response()->json(['message' => 'Mining record not found'], 404);
//     }

//     $coin        = Coin::where('name', $mining->coin_type)->first();
//     $totalMonths = (int)($coin ? $coin->mining_period : 24);

//     $currentProgress   = (float) $mining->progress;
//     $remainingProgress = 100 - $currentProgress;

//     /*
//     =====================
//     FIRST TIME ACTIVATE
//     =====================
//     */
//     if (!$mining->start_date) {
//         $start         = now();
//         $firstProgress = round((1 / $totalMonths) * 100, 2);
//         $nextDue       = $start->copy()->addDays(30);
//         $endDate       = $start->copy()->addDays($totalMonths * 30);

//         $mining->update([
//             'start_date'        => $start,
//             'monthly_due_date'  => $nextDue,
//             'end_date'          => $endDate,
//             'is_active'         => true,
//             'last_activated_at' => now(),
//             'progress'          => $firstProgress,
//             'current_month'     => 1,
//         ]);

//         DB::table('user_mining_history')->insert([
//             'user_id'          => $user->id,
//             'coin_type'        => $mining->coin_type,
//             'start_date'       => $start,
//             'end_date'         => $endDate,
//             'monthly_due_date' => $nextDue,
//             'created_at'       => now(),
//             'updated_at'       => now(),
//         ]);

//         return response()->json([
//             'message'  => 'Mining started successfully',
//             'progress' => $firstProgress,
//         ]);
//     }

//     /*
//     =====================
//     EARLY ACTIVATION (Before due date)
//     =====================
//     */
//     if ($mining->monthly_due_date && now()->lt($mining->monthly_due_date)) {

//         if ($mining->next_cycle_activated_at) {
//             return response()->json(['message' => 'Next cycle already activated']);
//         }

//         $earlyDays = (int) now()->diffInDays(Carbon::parse($mining->monthly_due_date));
//         $earlyDays = max(0, $earlyDays - 1);

//         $newEndDate = Carbon::parse($mining->end_date)->subDays($earlyDays);
//         $nextDue    = now()->copy()->addDays(30);

//         $remainingMonths = max(1, $totalMonths - $mining->current_month);
//         $monthlyProgress = $remainingProgress / $remainingMonths;
//         $newProgress     = min(100, $currentProgress + $monthlyProgress);

//         $mining->update([
//             'end_date'                => $newEndDate,
//             'monthly_due_date'        => $nextDue,
//             'pending_activation'      => true,
//             'next_cycle_activated_at' => now(),
//             'last_activated_at'       => now(),
//             'progress'                => round($newProgress, 2),
//             'current_month'           => $mining->current_month + 1,
//         ]);

//         // ✅ Same date duplicate check
//         $todayHistory = DB::table('user_mining_history')
//             ->where('user_id', $user->id)
//             ->whereDate('start_date', now()->toDateString())
//             ->first();

//         if ($todayHistory) {
//             DB::table('user_mining_history')
//                 ->where('id', $todayHistory->id)
//                 ->update([
//                     'end_date'         => $newEndDate,
//                     'monthly_due_date' => $nextDue,
//                     'updated_at'       => now(),
//                 ]);
//         } else {
//             DB::table('user_mining_history')->insert([
//                 'user_id'          => $user->id,
//                 'coin_type'        => $mining->coin_type,
//                 'start_date'       => now(),
//                 'end_date'         => $newEndDate,
//                 'monthly_due_date' => $nextDue,
//                 'created_at'       => now(),
//                 'updated_at'       => now(),
//             ]);
//         }

//         return response()->json([
//             'message'    => 'Early activation successful',
//             'progress'   => round($newProgress, 2),
//             'end_date'   => $newEndDate,
//             'early_days' => $earlyDays,
//         ]);
//     }

//     /*
//     =====================
//     MONTH COMPLETED (due date passed)
//     =====================
//     */
//     if (!$mining->monthly_due_date || now()->gte($mining->monthly_due_date)) {

//         $monthsSinceStart = (int)(
//             Carbon::parse($mining->start_date)
//                 ->diffInDays(Carbon::parse($mining->monthly_due_date)) / 30
//         );
//         $missedMonths = ($monthsSinceStart + 1) - $mining->current_month;
//         $missedMonths = max(0, $missedMonths);

//         if ($missedMonths > 0) {
//             $newEndDate              = Carbon::parse($mining->end_date)->addDays($missedMonths * 30);
//             $mining->inactive_months += $missedMonths;
//         } else {
//             $newEndDate = Carbon::parse($mining->end_date);
//         }

//         $totalDaysLate = (int) Carbon::parse($mining->monthly_due_date)->diffInDays(now());
//         $lateDays      = max(0, $totalDaysLate - ($missedMonths * 30) - 1);

//         if ($lateDays > 0) {
//             $newEndDate = $newEndDate->copy()->addDays($lateDays);
//         }

//         $nextDue         = now()->copy()->addDays(30);
//         $remainingMonths = max(1, $totalMonths - $mining->current_month);
//         $monthlyProgress = $remainingProgress / $remainingMonths;
//         $newProgress     = min(100, $currentProgress + $monthlyProgress);

//         $mining->update([
//             'progress'                => round($newProgress, 2),
//             'monthly_due_date'        => $nextDue,
//             'pending_activation'      => false,
//             'next_cycle_activated_at' => null,
//             'last_activated_at'       => now(),
//             'end_date'                => $newEndDate,
//             'current_month'           => $mining->current_month + 1,
//             'inactive_months'         => $mining->inactive_months,
//         ]);

//         // ✅ Same date duplicate check
//         $todayHistory = DB::table('user_mining_history')
//             ->where('user_id', $user->id)
//             ->whereDate('start_date', now()->toDateString())
//             ->first();

//         if ($todayHistory) {
//             DB::table('user_mining_history')
//                 ->where('id', $todayHistory->id)
//                 ->update([
//                     'end_date'         => $newEndDate,
//                     'monthly_due_date' => $nextDue,
//                     'updated_at'       => now(),
//                 ]);
//         } else {
//             DB::table('user_mining_history')->insert([
//                 'user_id'          => $user->id,
//                 'coin_type'        => $mining->coin_type,
//                 'start_date'       => now(),
//                 'end_date'         => $newEndDate,
//                 'monthly_due_date' => $nextDue,
//                 'created_at'       => now(),
//                 'updated_at'       => now(),
//             ]);
//         }

//         return response()->json([
//             'message'       => 'Mining updated',
//             'progress'      => round($newProgress, 2),
//             'end_date'      => $newEndDate,
//             'missed_months' => $missedMonths,
//             'late_days'     => $lateDays,
//         ]);
//     }
// }


public function activate(Request $request)
{
    $user = auth()->user();
    if (!$user) {
        return response()->json(['message' => 'Unauthenticated'], 401);
    }

    $mining = UserMining::where('user_id', $user->id)->first();
    if (!$mining) {
        return response()->json(['message' => 'Mining record not found'], 404);
    }

    $coin        = Coin::where('name', $mining->coin_type)->first();
    $totalMonths = (int)($coin ? $coin->mining_period : 24);

    $currentProgress   = (float) $mining->progress;
    $remainingProgress = 100 - $currentProgress;

    /*
    =====================
    FIRST TIME ACTIVATE
    =====================
    */
    if (!$mining->start_date) {
        $start         = now();
        $firstProgress = round((1 / $totalMonths) * 100, 2);
        $nextDue       = $start->copy()->addDays(30);
        $endDate       = $start->copy()->addDays($totalMonths * 30);

        $mining->update([
            'start_date'        => $start,
            'monthly_due_date'  => $nextDue,
            'end_date'          => $endDate,
            'is_active'         => true,
            'last_activated_at' => now(),
            'progress'          => $firstProgress,
            'current_month'     => 1,
        ]);

        DB::table('user_mining_history')->insert([
            'user_id'          => $user->id,
            'coin_type'        => $mining->coin_type,
            'start_date'       => $start,
            'end_date'         => $endDate,
            'monthly_due_date' => $nextDue,
            'created_at'       => now(),
            'updated_at'       => now(),
        ]);

        return response()->json([
            'message'  => 'Mining started successfully',
            'progress' => $firstProgress,
        ]);
    }

    /*
    =====================
    EARLY ACTIVATION
    =====================
    */
    if ($mining->monthly_due_date && now()->lt($mining->monthly_due_date)) {

        $nextDue = now()->copy()->addDays(30);

        // ❌ NO progress change
        $newProgress = $currentProgress;

        $mining->update([
            'monthly_due_date'   => $nextDue,
            'pending_activation' => true,
            'last_activated_at'  => now(),
            'progress'           => $currentProgress, // unchanged
            'current_month'      => $mining->current_month, // unchanged
        ]);

        // ✅ History check
        $todayHistory = DB::table('user_mining_history')
            ->where('user_id', $user->id)
            ->whereDate('start_date', now()->toDateString())
            ->first();

        if ($todayHistory) {
            DB::table('user_mining_history')
                ->where('id', $todayHistory->id)
                ->update([
                    'monthly_due_date' => $nextDue,
                    'updated_at'       => now(),
                ]);
        } else {
            DB::table('user_mining_history')->insert([
                'user_id'          => $user->id,
                'coin_type'        => $mining->coin_type,
                'start_date'       => now(),
                'end_date'         => $mining->end_date, // unchanged
                'monthly_due_date' => $nextDue,
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        }

        return response()->json([
            'message'  => 'Early activation successful',
            'progress' => $currentProgress,
        ]);
    }

    /*
    =====================
    MONTH COMPLETED
    =====================
    */
    if (!$mining->monthly_due_date || now()->gte($mining->monthly_due_date)) {

        $monthsSinceStart = (int)(
            Carbon::parse($mining->start_date)
                ->diffInDays(Carbon::parse($mining->monthly_due_date)) / 30
        );

        $missedMonths = ($monthsSinceStart + 1) - $mining->current_month;
        $missedMonths = max(0, $missedMonths);

        if ($missedMonths > 0) {
            $newEndDate              = Carbon::parse($mining->end_date)->addDays($missedMonths * 30);
            $mining->inactive_months += $missedMonths;
        } else {
            $newEndDate = Carbon::parse($mining->end_date);
        }

        $totalDaysLate = (int) Carbon::parse($mining->monthly_due_date)->diffInDays(now());
        $lateDays      = max(0, $totalDaysLate - ($missedMonths * 30) - 1);

        if ($lateDays > 0) {
            $newEndDate = $newEndDate->copy()->addDays($lateDays);
        }

        $nextDue         = now()->copy()->addDays(30);
        $remainingMonths = max(1, $totalMonths - $mining->current_month);
        $monthlyProgress = $remainingProgress / $remainingMonths;
        $newProgress     = min(100, $currentProgress + $monthlyProgress);

        $mining->update([
            'progress'           => round($newProgress, 2),
            'monthly_due_date'   => $nextDue,
            'pending_activation' => false,
            'last_activated_at'  => now(),
            'end_date'           => $newEndDate,
            'current_month'      => $mining->current_month + 1,
            'inactive_months'    => $mining->inactive_months,
        ]);

        // ✅ History check
        $todayHistory = DB::table('user_mining_history')
            ->where('user_id', $user->id)
            ->whereDate('start_date', now()->toDateString())
            ->first();

        if ($todayHistory) {
            DB::table('user_mining_history')
                ->where('id', $todayHistory->id)
                ->update([
                    'end_date'         => $newEndDate,
                    'monthly_due_date' => $nextDue,
                    'updated_at'       => now(),
                ]);
        } else {
            DB::table('user_mining_history')->insert([
                'user_id'          => $user->id,
                'coin_type'        => $mining->coin_type,
                'start_date'       => now(),
                'end_date'         => $newEndDate,
                'monthly_due_date' => $nextDue,
                'created_at'       => now(),
                'updated_at'       => now(),
            ]);
        }

        return response()->json([
            'message'       => 'Mining updated',
            'progress'      => round($newProgress, 2),
            'end_date'      => $newEndDate,
            'missed_months' => $missedMonths,
            'late_days'     => $lateDays,
        ]);
    }
}


// 07-04



// public function activate(Request $request)
// {
//     $user = auth()->user();

//     if (!$user) {
//         return response()->json(['message' => 'Unauthenticated'], 401);
//     }

//     $mining = UserMining::where('user_id', $user->id)->first();

//     if (!$mining) {
//         return response()->json(['message' => 'Mining record not found'], 404);
//     }

//     $coin        = Coin::where('name', $mining->coin_type)->first();
//     $totalMonths = (int) ($coin ? $coin->mining_period : 24);

//     $currentProgress   = (float) $mining->progress;
//     $remainingProgress = 100 - $currentProgress;

//     /*
//     =====================
//     FIRST TIME ACTIVATE
//     =====================
//     */
//     if (!$mining->start_date) {

//         $start         = now();
//         $firstProgress = round((1 / $totalMonths) * 100, 2);
//         $nextDue       = $start->copy()->addDays(30);
//         $endDate       = $start->copy()->addDays($totalMonths * 30);

//         $mining->update([
//             'start_date'        => $start,
//             'monthly_due_date'  => $nextDue,
//             'end_date'          => $endDate,
//             'is_active'         => true,
//             'last_activated_at' => now(),
//             'progress'          => $firstProgress,
//             'current_month'     => 1,
//             'pending_activation'=> false,
//             'next_cycle_activated_at' => null,
//         ]);

//         DB::table('user_mining_history')->insert([
//             'user_id'          => $user->id,
//             'coin_type'        => $mining->coin_type,
//             'start_date'       => $start,
//             'end_date'         => $endDate,
//             'monthly_due_date' => $nextDue,
//             'created_at'       => now(),
//             'updated_at'       => now(),
//         ]);

//         return response()->json([
//             'message'  => 'Mining started successfully',
//             'progress' => $firstProgress,
//         ]);
//     }

//     /*
//     =====================
//     EARLY ACTIVATION (Before due date)
//     =====================
//     */
//     if ($mining->monthly_due_date && now()->lt($mining->monthly_due_date)) {

//         if ($mining->pending_activation) {
//             return response()->json([
//                 'message' => 'Activation already stored for next cycle'
//             ]);
//         }

//         // ✅ ONLY STORE (no progress / no end_date change)
//         $mining->update([
//             'pending_activation'      => true,
//             'next_cycle_activated_at' => now(),
//         ]);

//         return response()->json([
//             'message' => 'Activation stored for next cycle'
//         ]);
//     }

//     /*
//     =====================
//     MONTH COMPLETED (due date passed)
//     =====================
//     */
//     if (!$mining->monthly_due_date || now()->gte($mining->monthly_due_date)) {

//         // 🔢 Calculate missed months
//         $monthsSinceStart = (int)(
//             Carbon::parse($mining->start_date)
//                 ->diffInDays(now()) / 30
//         );

//         $expectedMonth = $monthsSinceStart + 1;
//         $missedMonths  = max(0, $expectedMonth - $mining->current_month - 1);

//         // 📅 Extend end_date for missed months
//         $newEndDate = Carbon::parse($mining->end_date);

//         if ($missedMonths > 0) {
//             $newEndDate = $newEndDate->addDays($missedMonths * 30);
//             $mining->inactive_months += $missedMonths;
//         }

//         // ⏱ Late days
//         $lateDays = 0;
//         if ($mining->monthly_due_date) {
//             $lateDays = max(0, Carbon::parse($mining->monthly_due_date)->diffInDays(now()) - ($missedMonths * 30));
//             if ($lateDays > 0) {
//                 $newEndDate = $newEndDate->addDays($lateDays);
//             }
//         }

//         // 📊 Progress calculation
//         $remainingMonths = max(1, $totalMonths - $mining->current_month);
//         $monthlyProgress = $remainingProgress / $remainingMonths;

//         // ✅ Only give progress if activated
//         if ($mining->pending_activation) {
//             $newProgress = min(100, $currentProgress + $monthlyProgress);
//         } else {
//             $newProgress = $currentProgress; // missed month
//         }

//         $nextDue = now()->copy()->addDays(30);

//         $mining->update([
//             'progress'                => round($newProgress, 2),
//             'monthly_due_date'        => $nextDue,
//             'pending_activation'      => false,
//             'next_cycle_activated_at' => null,
//             'last_activated_at'       => now(),
//             'end_date'                => $newEndDate,
//             'current_month'           => $mining->current_month + 1,
//             'inactive_months'         => $mining->inactive_months,
//         ]);

//         // ✅ History insert/update (no duplicate same day)
//         $todayHistory = DB::table('user_mining_history')
//             ->where('user_id', $user->id)
//             ->whereDate('start_date', now()->toDateString())
//             ->first();

//         if ($todayHistory) {
//             DB::table('user_mining_history')
//                 ->where('id', $todayHistory->id)
//                 ->update([
//                     'end_date'         => $newEndDate,
//                     'monthly_due_date' => $nextDue,
//                     'updated_at'       => now(),
//                 ]);
//         } else {
//             DB::table('user_mining_history')->insert([
//                 'user_id'          => $user->id,
//                 'coin_type'        => $mining->coin_type,
//                 'start_date'       => now(),
//                 'end_date'         => $newEndDate,
//                 'monthly_due_date' => $nextDue,
//                 'created_at'       => now(),
//                 'updated_at'       => now(),
//             ]);
//         }

//         return response()->json([
//             'message'       => 'Mining updated',
//             'progress'      => round($newProgress, 2),
//             'end_date'      => $newEndDate,
//             'missed_months' => $missedMonths,
//             'late_days'     => $lateDays,
//         ]);
//     }
// }

















public function dashboard()

{

    $user = auth()->user();



    if(!$user){

        return response()->json([

            'message'=>'Unauthenticated'

        ],401);

    }



    $mining = UserMining::where('user_id',$user->id)->first();



    if(!$mining){

        return response()->json([

            'message'=>'Mining record not found'

        ],404);

    }

    $coin = Coin::where('name',$mining->coin_type)->first();
        $totalMonths = $coin ? $coin->mining_period : 24;


    // Calculate dynamic end date

    // $endDate = Carbon::parse($mining->start_date)

    //             ->addMonths(24 + $mining->inactive_months);

$endDate = Carbon::parse($mining->start_date)
            ->addMonths($totalMonths + $mining->inactive_months);

    // Mining completed check

    if($mining->progress >= 100){



        return response()->json([



            'coin_type'=>$mining->coin_type,

            'coin_image' => $coin
            ? asset($coin->image)
            : null,
            
            'coin_image2' => $coin
            ? asset($coin->image2)
            : null,

            'start_date'=>$mining->start_date,



            'end_date'=>$endDate,



            'progress'=>100,



            'inactive_months'=>$mining->inactive_months,



            'mining_status'=>'Completed',



            'message'=>'Mining completed successfully'



        ]);

    }



    return response()->json([



        'coin_type'=>$mining->coin_type,

            'coin_image' => $coin
            ? asset($coin->image)
            : null,
            
             'coin_image2' => $coin
            ? asset($coin->image2)
            : null,

        'start_date'=>$mining->start_date,



        'end_date'=>$endDate,



        'monthly_due_date'=>$mining->monthly_due_date,



        'progress'=>round($mining->progress,2),



        'inactive_months'=>$mining->inactive_months,



        // 'mining_status'=>$mining->is_active ? 'Active':'Inactive',

'mining_status' => (function() use ($mining, $user) {
    if (!$mining->is_active) return 'Inactive';
    
    // ✅ Check if current month has entry in user_mining_history
    $currentMonthEntry = DB::table('user_mining_history')
        ->where('user_id', $user->id)
        ->whereYear('start_date', now()->year)
        ->whereMonth('start_date', now()->month)
        ->first();
    
    if (!$currentMonthEntry) return 'Inactive';
    
    return 'Active';
})(),

        'next_activation_required'=>$mining->monthly_due_date



    ]);

}



public function progress()

{

    $user = auth()->user();

    if (!$user) {

    return response()->json([

        'message' => 'Unauthenticated'

    ], 401);

}

    $mining = UserMining::where('user_id', $user->id)->first();

if (!$mining) {

    return response()->json([

        'message' => 'Mining record not found'

    ], 404);

}

    return response()->json([

        'total_progress' => $mining->progress,

        'months_completed' => floor($mining->progress / (100 / 24)),

        'total_months' => 24

    ]);

}



public function changePassword(Request $request)

{

    $request->validate([

        'old_password' => 'required',

        'new_password' => 'required|min:6|confirmed'

    ]);



    $user = auth()->user();



    if (!Hash::check($request->old_password, $user->password)) {

        return response()->json([

            'message' => 'Old password is incorrect'

        ], 400);

    }



    $user->update([

        'password' => Hash::make($request->new_password)

    ]);



    return response()->json([

        'message' => 'Password changed successfully'

    ]);

}



public function profile(){

    $user = auth()->user();

    $user->photo = $user->photo 

        ? url('assets/images/profile/'.$user->photo)

        : null;

      return response()->json([

        'user' => $user,

        

    ]);

}



public function updateProfile(Request $request)

{

    $user = auth()->user();



    $request->validate([

        'name' => 'required|string|min:2',

        'dob' => 'required|date',

        'country_id' => 'required|exists:countries,id',

        'postal_code' => 'nullable|string'

    ]);



    $country = Country::find($request->country_id);



    /* AGE VALIDATION */

    $age = \Carbon\Carbon::parse($request->dob)->age;

    if ($age < 18) {

        return response()->json([

            'message' => 'You must be at least 18 years old'

        ], 422);

    }



    /* POSTAL CODE VALIDATION */

    if ($country->postal_regex && $request->postal_code) {

        if (!preg_match('/'.$country->postal_regex.'/', $request->postal_code)) {

            return response()->json([

                'message' => 'Invalid postal code for selected country'

            ], 422);

        }

    }



    $user->update([

        'name' => $request->name,

        'dob' => $request->dob,

        'country_id' => $request->country_id,

        'postal_code' => $request->postal_code

    ]);



    return response()->json([

        'message' => 'Profile updated successfully',

        'user' => $user

    ]);

}



public function updateProfilePhoto(Request $request)

{

    $request->validate([

        'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048'

    ]);



    $user = Auth::user();



    if ($request->hasFile('photo')) {



        $file = $request->file('photo');



        $filename = time().'_'.$file->getClientOriginalName();



        $destination = public_path('assets/images/profile');



        $file->move($destination, $filename);



        // delete old photo

        if ($user->photo && file_exists(public_path('assets/images/profile/'.$user->photo))) {

            unlink(public_path('assets/images/profile/'.$user->photo));

        }



        $user->photo = $filename;

        $user->save();

    }



    return response()->json([

        'success' => true,

        'message' => 'Profile photo updated successfully',

        // 'photo_url' => asset('assets/images/profile/'.$filename)

    ]);

}



//   public function miningHistory(Request $request)

//     {

//         $userId = Auth::id(); // login user

// //  $userId = Auth::user();

//         $mining = UserMining::where('user_id', $userId)

//             ->select('coin_type','start_date','monthly_due_date','is_active')

//             ->get();



//         $data = $mining->map(function ($item) {

//             return [

//                 'coin' => $item->coin_type,

//                 'start_date' => $item->start_date,

//                 'end_date' => $item->monthly_due_date,

//                 'mining_status' => $item->is_active ? 'Active' : 'Inactive',

//             ];

//         });



//         return response()->json([

//             'status' => true,

//             'message' => 'Mining history fetched successfully',

//             'data' => $data

//         ]);

//     }




// indhu

// public function miningHistory(Request $request)
// {
//     $user = Auth::user();

//     if (!$user) {
//         return response()->json([
//             'status' => false,
//             'message' => 'Unauthenticated'
//         ], 401);
//     }

//     $mining = UserMining::where('user_id', $user->id)->first();

//     if (!$mining || !$mining->start_date) {
//         return response()->json([
//             'status' => false,
//             'message' => 'Mining not started'
//         ], 404);
//     }

//     $startDate = Carbon::parse($mining->start_date);
//     $now = now();
//     $monthlyProgress = 100 / 24;

//     $data = [];
//     $i = 0;

//     while (true) {

//         $monthStart = $startDate->copy()->addMonths($i);
//         $monthEnd = $monthStart->copy()->addMonth();

//         // ðŸš¨ STOP when future month starts
//         if ($monthStart->gt($now)) {
//             break;
//         }

//         $completedMonths = floor($mining->progress / $monthlyProgress);

//         if ($i < $completedMonths) {
//             $status = 'Active';
//         } elseif ($monthStart <= $now && $monthEnd >= $now) {
//             $status = 'Active'; // current month
//         } else {
//             $status = 'Inactive';
//         }

//         $data[] = [
//             'coin' => $mining->coin_type,
//             'start_date' => $monthStart->format('d.m.Y'),
//             'end_date' => $monthEnd->format('d.m.Y'),
//             'mining_status' => $status
//         ];

//         $i++;
//     }

//     return response()->json([
//         'status' => true,
//         'message' => 'Mining history fetched successfully',
//         'data' => $data
//     ]);
// }




// sowmii
// public function miningHistory(Request $request)
// {
//     $user = Auth::user();

//     if (!$user) {
//         return response()->json([
//             'status' => false,
//             'message' => 'Unauthenticated'
//         ], 401);
//     }

//     $now = now();
//     $data = [];

//     // 🔹 GET CURRENT PLAN
//     $mining = UserMining::where('user_id', $user->id)->first();

//     if (!$mining || !$mining->start_date) {
//         return response()->json([
//             'status' => false,
//             'message' => 'Mining not started'
//         ], 404);
//     }

//     // 🔹 GET HISTORY
//     $histories = DB::table('user_mining_history')
//         ->where('user_id', $user->id)
//         ->get();

//     $monthlyProgress = 100 / 24;

//     // =====================================================
//     // 🔥 1. HISTORY LOOP (ALL INACTIVE)
//     // =====================================================
//     foreach ($histories as $history) {

//         $startDate = Carbon::parse($history->start_date);
//         $i = 0;

//         while (true) {

//             $monthStart = $startDate->copy()->addMonths($i);
//             $monthEnd   = $monthStart->copy()->addMonth();

//             if ($monthStart >= Carbon::parse($history->end_date)) {
//                 break;
//             }

//             if ($monthEnd > Carbon::parse($history->end_date)) {
//                 $monthEnd = Carbon::parse($history->end_date);
//             }

//             $data[] = [
//                 'coin' => $history->coin_type,
//                 'start_date' => $monthStart->format('d.m.Y'),
//                 'end_date' => $monthEnd->format('d.m.Y'),
//                 'mining_status' => 'Inactive' // ✅ always inactive
//             ];

//             $i++;
//         }
//     }

//     // =====================================================
//     // 🔥 2. CURRENT PLAN LOOP (YOUR ORIGINAL LOGIC)
//     // =====================================================
//     $startDate = Carbon::parse($mining->start_date);
//     $i = 0;

//     while (true) {

//         $monthStart = $startDate->copy()->addMonths($i);
//         $monthEnd   = $monthStart->copy()->addMonth();

//         if ($monthStart->gt($now)) {
//             break;
//         }

//         $completedMonths = floor($mining->progress / $monthlyProgress);

//         if ($i < $completedMonths) {
//             $status = 'Inactive'; // ✅ changed (old months inactive)
//         } elseif ($monthStart <= $now && $monthEnd >= $now) {
//             $status = 'Active'; // ✅ only current active
//         } else {
//             $status = 'Inactive';
//         }

//         $data[] = [
//             'coin' => $mining->coin_type,
//             'start_date' => $monthStart->format('d.m.Y'),
//             'end_date' => $monthEnd->format('d.m.Y'),
//             'mining_status' => $status
//         ];

//         $i++;
//     }

//     // 🔹 SORT BY DATE
//     usort($data, function ($a, $b) {
//         return strtotime(str_replace('.', '-', $a['start_date']))
//              - strtotime(str_replace('.', '-', $b['start_date']));
//     });

//     return response()->json([
//         'status' => true,
//         'message' => 'Mining history fetched successfully',
//         'data' => $data
//     ]);
// }




// 01-04
// public function miningHistory(Request $request)
// {
//     $user = Auth::user();
//     if (!$user) {
//         return response()->json(['status' => false, 'message' => 'Unauthenticated'], 401);
//     }

//     $today = Carbon::today();

//     // ✅ user_mining_history table-லேர்ந்து all unique plans எடு
//     $histories = DB::table('user_mining_history')
//         ->where('user_id', $user->id)
//         ->orderBy('start_date', 'asc')
//         ->get();

//     if ($histories->isEmpty()) {
//         return response()->json(['status' => false, 'message' => 'Mining not started'], 404);
//     }

//     // ✅ Each coin_type-க்கு min start_date, max end_date எடு
//     $plans = DB::table('user_mining_history')
//         ->where('user_id', $user->id)
//         ->select(
//             'coin_type',
//             DB::raw('MIN(start_date) as plan_start'),
//             DB::raw('MAX(end_date) as plan_end')
//         )
//         ->groupBy('coin_type')
//         ->get();

//     $data = [];

//     foreach ($plans as $plan) {
//         $coin      = Coin::where('name', $plan->coin_type)->first();
//         $maxCycles = (int)($coin ? $coin->mining_period : 24) + 12; // buffer

//         $startDate         = Carbon::parse($plan->plan_start);
//         $endDate           = Carbon::parse($plan->plan_end);
//         $currentCycleStart = $startDate->copy();

//         for ($i = 0; $i < $maxCycles; $i++) {
//             $currentCycleEnd = $currentCycleStart->copy()->addDays(30);

//             // ✅ Future months வேண்டாம்
//             if ($currentCycleStart->gt($today)) {
//                 break;
//             }

//             // ✅ History table-ல் இந்த coin + இந்த cycle-ல் entry இருக்கா?
//             $isActive = $histories->contains(function ($history) use ($currentCycleStart, $currentCycleEnd, $plan) {
//                 if ($history->coin_type !== $plan->coin_type) {
//                     return false;
//                 }
//                 $hDate = Carbon::parse($history->start_date);
//                 return $hDate->gte($currentCycleStart) && $hDate->lt($currentCycleEnd);
//             });

//             $data[] = [
//                 'coin'          => $plan->coin_type,
//                 'start_date'    => $currentCycleStart->format('d.m.Y'),
//                 'end_date'      => $currentCycleEnd->format('d.m.Y'),
//                 'mining_status' => $isActive ? 'Active' : 'Inactive',
//             ];

//             $currentCycleStart = $currentCycleEnd->copy();

//             if ($currentCycleStart->gt($endDate)) {
//                 break;
//             }
//         }
//     }

//     // ✅ Date order sort
//     usort($data, function ($a, $b) {
//         $dateA = Carbon::createFromFormat('d.m.Y', $a['start_date']);
//         $dateB = Carbon::createFromFormat('d.m.Y', $b['start_date']);
//         return $dateA->lt($dateB) ? -1 : 1;
//     });



//     $final    = [];
// $seenKeys = [];
// foreach ($data as $item) {
//     $key = $item['coin'] . '_' . $item['start_date'];
//     if (!isset($seenKeys[$key])) {
//         $seenKeys[$key] = true;
//         $final[] = $item;
//     }
// }

// return response()->json([
//     'status'  => true,
//     'message' => 'Mining history fetched successfully',
//     'data'    => $final,
// ]);
// }



// public function miningHistory(Request $request)
// {
//     $user = Auth::user();

//     if (!$user) {
//         return response()->json([
//             'status' => false,
//             'message' => 'Unauthenticated'
//         ], 401);
//     }

//     $today = Carbon::today();

//     // ✅ Get history
//     $histories = DB::table('user_mining_history')
//         ->where('user_id', $user->id)
//         ->orderBy('start_date', 'asc')
//         ->get();

//     if ($histories->isEmpty()) {
//         return response()->json([
//             'status' => false,
//             'message' => 'Mining not started'
//         ], 404);
//     }

//     // ✅ Get plan start & end
//     $plans = DB::table('user_mining_history')
//         ->where('user_id', $user->id)
//         ->select(
//             'coin_type',
//             DB::raw('MIN(start_date) as plan_start'),
//             DB::raw('MAX(end_date) as plan_end')
//         )
//         ->groupBy('coin_type')
//         ->get();

//     $data = [];

//     foreach ($plans as $plan) {

//         $coin = Coin::where('name', $plan->coin_type)->first();

//         // ✅ Keep your logic (no major change)
//         $maxCycles = (int)($coin ? $coin->mining_period : 24);

//         $startDate = Carbon::parse($plan->plan_start);
//         $endDate   = Carbon::parse($plan->plan_end);

//         $currentCycleStart = $startDate->copy();

//         for ($i = 0; $i < $maxCycles; $i++) {

//             // 🔥 FIX: stop EXACTLY at end_date
//             if ($currentCycleStart->gte($endDate)) {
//                 break;
//             }

//             $currentCycleEnd = $currentCycleStart->copy()->addDays(30);

//             // 🔥 Prevent تجاوز end_date
//             if ($currentCycleEnd->gt($endDate)) {
//                 $currentCycleEnd = $endDate->copy();
//             }

//             // ✅ Active check
//             $isActive = $histories->contains(function ($history) use ($currentCycleStart, $currentCycleEnd, $plan) {

//                 if ($history->coin_type !== $plan->coin_type) {
//                     return false;
//                 }

//                 $hDate = Carbon::parse($history->start_date);

//                 return $hDate->gte($currentCycleStart) && $hDate->lt($currentCycleEnd);
//             });

//             $data[] = [
//                 'coin'          => $plan->coin_type,
//                 'start_date'    => $currentCycleStart->format('d.m.Y'),
//                 'end_date'      => $currentCycleEnd->format('d.m.Y'),
//                 'mining_status' => $isActive ? 'Active' : 'Inactive',
//             ];

//             $currentCycleStart = $currentCycleEnd->copy();
//         }
//     }

//     // ✅ Sort by date
//     usort($data, function ($a, $b) {
//         $dateA = Carbon::createFromFormat('d.m.Y', $a['start_date']);
//         $dateB = Carbon::createFromFormat('d.m.Y', $b['start_date']);
//         return $dateA->lt($dateB) ? -1 : 1;
//     });

//     // ✅ Remove duplicates
//     $final = [];
//     $seenKeys = [];

//     foreach ($data as $item) {
//         $key = $item['coin'] . '_' . $item['start_date'];

//         if (!isset($seenKeys[$key])) {
//             $seenKeys[$key] = true;
//             $final[] = $item;
//         }
//     }

//     return response()->json([
//         'status'  => true,
//         'message' => 'Mining history fetched successfully',
//         'data'    => $final
//     ]);
// }



// public function miningHistory(Request $request)
// {
//     $user = Auth::user();

//     if (!$user) {
//         return response()->json([
//             'status' => false,
//             'message' => 'Unauthenticated'
//         ], 401);
//     }

//     $today = Carbon::today();

//     // ✅ Get history
//     $histories = DB::table('user_mining_history')
//         ->where('user_id', $user->id)
//         ->orderBy('start_date', 'asc')
//         ->get();

//     if ($histories->isEmpty()) {
//         return response()->json([
//             'status' => false,
//             'message' => 'Mining not started'
//         ], 404);
//     }

//     // ✅ Get plan start & end
//     $plans = DB::table('user_mining_history')
//         ->where('user_id', $user->id)
//         ->select(
//             'coin_type',
//             DB::raw('MIN(start_date) as plan_start'),
//             DB::raw('MAX(end_date) as plan_end')
//         )
//         ->groupBy('coin_type')
//         ->get();

//     $data = [];

//     foreach ($plans as $plan) {

//         $coin = Coin::where('name', $plan->coin_type)->first();

//         $maxCycles = (int)($coin ? $coin->mining_period : 24);

//         $startDate = Carbon::parse($plan->plan_start);
//         $endDate   = Carbon::parse($plan->plan_end);

//         $currentCycleStart = $startDate->copy();

//         for ($i = 0; $i < $maxCycles; $i++) {

//             // ✅ stop at end_date
//             if ($currentCycleStart->gte($endDate)) {
//                 break;
//             }

//             // ✅ stop at today - future காட்டாது
//             if ($currentCycleStart->gt(now())) {
//                 break;
//             }

//             $currentCycleEnd = $currentCycleStart->copy()->addDays(30);

//             if ($currentCycleEnd->gt($endDate)) {
//                 $currentCycleEnd = $endDate->copy();
//             }

//             // ✅ Active check
//             $isActive = $histories->contains(function ($history) use ($currentCycleStart, $currentCycleEnd, $plan) {

//                 if ($history->coin_type !== $plan->coin_type) {
//                     return false;
//                 }

//                 $hDate = Carbon::parse($history->start_date);

//                 return $hDate->gte($currentCycleStart) && $hDate->lt($currentCycleEnd);
//             });

//             $data[] = [
//                 'coin'          => $plan->coin_type,
//                 'start_date'    => $currentCycleStart->format('d.m.Y'),
//                 'end_date'      => $currentCycleEnd->format('d.m.Y'),
//                 'mining_status' => $isActive ? 'Active' : 'Inactive',
//             ];

//             $currentCycleStart = $currentCycleEnd->copy();
//         }
        
        
        
        
        
        
        
        
//     }

//     // ✅ Sort by date
//     usort($data, function ($a, $b) {
//         $dateA = Carbon::createFromFormat('d.m.Y', $a['start_date']);
//         $dateB = Carbon::createFromFormat('d.m.Y', $b['start_date']);
//         return $dateA->lt($dateB) ? -1 : 1;
//     });

//     // ✅ Remove duplicates
//     $final    = [];
//     $seenKeys = [];

//     foreach ($data as $item) {
//         $key = $item['coin'] . '_' . $item['start_date'];

//         if (!isset($seenKeys[$key])) {
//             $seenKeys[$key] = true;
//             $final[]        = $item;
//         }
//     }

//     return response()->json([
//         'status'  => true,
//         'message' => 'Mining history fetched successfully',
//         'data'    => $final
//     ]);
// }


// public function miningHistory(Request $request)
// {
//     $user = Auth::user();
//     if (!$user) {
//         return response()->json(['status' => false, 'message' => 'Unauthenticated'], 401);
//     }

//     $histories = DB::table('user_mining_history')
//         ->where('user_id', $user->id)
//         ->orderBy('start_date', 'asc')
//         ->get();

//     if ($histories->isEmpty()) {
//         return response()->json(['status' => false, 'message' => 'Mining not started'], 404);
//     }

//     $plans = DB::table('user_mining_history')
//         ->where('user_id', $user->id)
//         ->select(
//             'coin_type',
//             DB::raw('MIN(start_date) as plan_start'),
//             DB::raw('MAX(end_date) as plan_end')
//         )
//         ->groupBy('coin_type')
//         ->get();

//     $data = [];

//     foreach ($plans as $plan) {
//         $coin      = Coin::where('name', $plan->coin_type)->first();
//         $maxCycles = (int)($coin ? $coin->mining_period : 24);
//         $endDate   = Carbon::parse($plan->plan_end);

//         // This plan's history rows only
//         $planHistories = $histories
//             ->where('coin_type', $plan->coin_type)
//             ->sortBy('start_date')
//             ->values();

//         $cycleCount        = 0;
//         $currentCycleStart = Carbon::parse($plan->plan_start);

//         for ($i = 0; $i < $maxCycles; $i++) {
//             if ($cycleCount >= $maxCycles) break;
//             if ($currentCycleStart->gte($endDate)) break;
//             if ($currentCycleStart->gt(now())) break;

//             $currentCycleEnd = $currentCycleStart->copy()->addDays(30);
//             if ($currentCycleEnd->gt($endDate)) {
//                 $currentCycleEnd = $endDate->copy();
//             }

//             // ✅ Is there an activation record in this window?
//             $matchingRow = $planHistories->first(function ($history) use ($currentCycleStart, $currentCycleEnd) {
//                 $hDate = Carbon::parse($history->start_date);
//                 return $hDate->gte($currentCycleStart) && $hDate->lt($currentCycleEnd);
//             });

//             if ($matchingRow) {
//                 // ✅ Active — use ACTUAL start_date from DB, end = actual start + 30 days
//                 $actualStart = Carbon::parse($matchingRow->start_date);
//                 $actualEnd   = $actualStart->copy()->addDays(30);
//                 if ($actualEnd->gt($endDate)) {
//                     $actualEnd = $endDate->copy();
//                 }

//                 $data[] = [
//                     'coin'          => $plan->coin_type,
//                     'start_date'    => $actualStart->format('d.m.Y'),
//                     'end_date'      => $actualEnd->format('d.m.Y'),
//                     'mining_status' => 'Active',
//                 ];

//                 // ✅ Next cycle starts from actual end (not fixed +30 from old start)
//                 $currentCycleStart = $actualEnd->copy();
//             } else {
//                 // ✅ Inactive — show fixed window as-is
//                 $data[] = [
//                     'coin'          => $plan->coin_type,
//                     'start_date'    => $currentCycleStart->format('d.m.Y'),
//                     'end_date'      => $currentCycleEnd->format('d.m.Y'),
//                     'mining_status' => 'Inactive',
//                 ];

//                 $currentCycleStart = $currentCycleEnd->copy();
//             }

//             $cycleCount++;
//         }
//     }

//     // Sort
//     usort($data, function ($a, $b) {
//         return Carbon::createFromFormat('d.m.Y', $a['start_date'])
//             ->lt(Carbon::createFromFormat('d.m.Y', $b['start_date'])) ? -1 : 1;
//     });

//     // Deduplicate
//     $final    = [];
//     $seenKeys = [];
//     foreach ($data as $item) {
//         $key = $item['coin'] . '_' . $item['start_date'];
//         if (!isset($seenKeys[$key])) {
//             $seenKeys[$key] = true;
//             $final[]        = $item;
//         }
//     }

//     return response()->json([
//         'status'  => true,
//         'message' => 'Mining history fetched successfully',
//         'data'    => $final
//     ]);
// }

// public function miningHistory(Request $request)
// {
//     $user = Auth::user();
//     if (!$user) {
//         return response()->json(['status' => false, 'message' => 'Unauthenticated'], 401);
//     }

//     $histories = DB::table('user_mining_history')
//         ->where('user_id', $user->id)
//         ->orderBy('start_date', 'asc')
//         ->get();

//     if ($histories->isEmpty()) {
//         return response()->json(['status' => false, 'message' => 'Mining not started'], 404);
//     }

//     // ✅ Max cycles — first coin's mining_period
//     $firstCoin = Coin::where('name', $histories->first()->coin_type)->first();
//     $maxCycles = (int)($firstCoin ? $firstCoin->mining_period : 24);

//     // ✅ Plan end = last row's end_date
//     $planEnd       = Carbon::parse($histories->last()->end_date);
//     $coinHistories = $histories->values();
//     $total         = $coinHistories->count();

//     $data       = [];
//     $cycleCount = 0;

//     for ($i = 0; $i < $total; $i++) {
//         if ($cycleCount >= $maxCycles) break;

//         $row        = $coinHistories[$i];
//         $cycleStart = Carbon::parse($row->start_date);

//         if ($cycleStart->gt(now())) break;

//         $thisDue = $row->monthly_due_date
//             ? Carbon::parse($row->monthly_due_date)
//             : $cycleStart->copy()->addDays(30);

//         // ✅ This active cycle end = due date
//         $cycleEnd = $thisDue->copy();
//         if ($cycleEnd->gt($planEnd)) $cycleEnd = $planEnd->copy();

//         // ✅ Next row start
//         $nextStart = ($i + 1 < $total)
//             ? Carbon::parse($coinHistories[$i + 1]->start_date)
//             : null;

//         // ✅ Add Active row
//         $data[] = [
//             'coin'          => $row->coin_type,
//             'start_date'    => $cycleStart->format('d.m.Y'),
//             'end_date'      => $cycleEnd->format('d.m.Y'),
//             'mining_status' => 'Active',
//         ];
//         $cycleCount++;

//         // ✅ Gap between this due and next row start = Inactive
//         if ($nextStart && $thisDue->lt($nextStart) && $cycleCount < $maxCycles) {
//             $gapCursor = $thisDue->copy();

//             while ($gapCursor->lt($nextStart) && $cycleCount < $maxCycles) {
//                 if ($gapCursor->gt(now())) break;

//                 $slotEnd  = $gapCursor->copy()->addDays(30);
//                 if ($slotEnd->gt($nextStart)) $slotEnd = $nextStart->copy();

//                 // ✅ Only full 30-day slots = Inactive
//                 // Partial = late days already in end_date, skip
//                 $slotDays = $gapCursor->diffInDays($slotEnd);
//                 if ($slotDays < 30) break;

//                 $data[] = [
//                     'coin'          => $row->coin_type,
//                     'start_date'    => $gapCursor->format('d.m.Y'),
//                     'end_date'      => $slotEnd->format('d.m.Y'),
//                     'mining_status' => 'Inactive',
//                 ];
//                 $cycleCount++;
//                 $gapCursor = $slotEnd->copy();
//             }
//         }
//     }

//     // Sort
//     usort($data, function ($a, $b) {
//         return Carbon::createFromFormat('d.m.Y', $a['start_date'])
//             ->lt(Carbon::createFromFormat('d.m.Y', $b['start_date'])) ? -1 : 1;
//     });

//     // Deduplicate
//     $final    = [];
//     $seenKeys = [];
//     foreach ($data as $item) {
//         $key = $item['coin'] . '_' . $item['start_date'];
//         if (!isset($seenKeys[$key])) {
//             $seenKeys[$key] = true;
//             $final[]        = $item;
//         }
//     }

//     return response()->json([
//         'status'  => true,
//         'message' => 'Mining history fetched successfully',
//         'data'    => $final
//     ]);
// }










// public function miningHistory(Request $request)
// {
//     $user = Auth::user();
//     if (!$user) {
//         return response()->json(['status' => false, 'message' => 'Unauthenticated'], 401);
//     }

//     $histories = DB::table('user_mining_history')
//         ->where('user_id', $user->id)
//         ->orderBy('start_date', 'asc')
//         ->get();

//     if ($histories->isEmpty()) {
//         return response()->json(['status' => false, 'message' => 'Mining not started'], 404);
//     }

//     // Get user_mining record for inactive_months info
//     $userMining = DB::table('user_mining')->where('user_id', $user->id)->first();
//     $inactiveMonths   = $userMining ? (int)$userMining->inactive_months : 0;
//     $currentMonth     = $userMining ? (int)$userMining->current_month : 0;

//     $firstCoin = Coin::where('name', $histories->first()->coin_type)->first();
//     $maxCycles = (int)($firstCoin ? $firstCoin->mining_period : 24);

//     $planEnd       = Carbon::parse($histories->last()->end_date);
//     $coinHistories = $histories->values();
//     $total         = $coinHistories->count();

//     // Build a set of known active due dates from history
//     $activeDueDates = $coinHistories->pluck('monthly_due_date')->filter()->map(
//         fn($d) => Carbon::parse($d)->format('Y-m-d')
//     )->toArray();

//     $data       = [];
//     $cycleCount = 0;

//     for ($i = 0; $i < $total; $i++) {
//         if ($cycleCount >= $maxCycles) break;

//         $row        = $coinHistories[$i];
//         $cycleStart = Carbon::parse($row->start_date);

//         if ($cycleStart->gt(now())) break;

//         $thisDue = Carbon::parse($row->monthly_due_date);
//         $cycleEnd = $thisDue->copy();
//         if ($cycleEnd->gt($planEnd)) $cycleEnd = $planEnd->copy();

//         $nextStart = ($i + 1 < $total)
//             ? Carbon::parse($coinHistories[$i + 1]->start_date)
//             : null;

//         // ✅ Determine if this cycle was active or inactive
//         // A cycle is inactive if its due date was skipped (not activated)
//         // Compare: if next row's start == this due, it was active
//         // If there's a jump, intermediate slots = inactive
//         $data[] = [
//             'coin'          => $row->coin_type,
//             'start_date'    => $cycleStart->format('d.m.Y'),
//             'end_date'      => $cycleEnd->format('d.m.Y'),
//             'mining_status' => 'Active',
//         ];
//         $cycleCount++;

//         // ✅ Check gap to next row — fill with Inactive slots
//         if ($nextStart && $thisDue->lt($nextStart) && $cycleCount < $maxCycles) {
//             $gapCursor = $thisDue->copy();

//             while ($gapCursor->lt($nextStart) && $cycleCount < $maxCycles) {
//                 if ($gapCursor->gt(now())) break;

//                 $slotEnd = $gapCursor->copy()->addDays(30);
//                 if ($slotEnd->gt($nextStart)) $slotEnd = $nextStart->copy();

//                 $slotDays = $gapCursor->diffInDays($slotEnd);
//                 if ($slotDays < 28) break; // allow ~28 days for month variance

//                 $data[] = [
//                     'coin'          => $row->coin_type,
//                     'start_date'    => $gapCursor->format('d.m.Y'),
//                     'end_date'      => $slotEnd->format('d.m.Y'),
//                     'mining_status' => 'Inactive',
//                 ];
//                 $cycleCount++;
//                 $gapCursor = $slotEnd->copy();
//             }
//         }
//     }

//     // ✅ If inactive_months > 0 but no inactive rows were generated,
//     // append trailing inactive months after the last history row
//     $generatedInactive = count(array_filter($data, fn($d) => $d['mining_status'] === 'Inactive'));

//     if ($inactiveMonths > 0 && $generatedInactive < $inactiveMonths && !empty($data)) {
//         $lastActive = end($data);
//         $cursor = Carbon::createFromFormat('d.m.Y', $lastActive['end_date']);

//         $remaining = $inactiveMonths - $generatedInactive;
//         for ($m = 0; $m < $remaining && $cycleCount < $maxCycles; $m++) {
//             if ($cursor->gt(now())) break;
//             $slotEnd = $cursor->copy()->addDays(30);
//             $data[] = [
//                 'coin'          => $lastActive['coin'],
//                 'start_date'    => $cursor->format('d.m.Y'),
//                 'end_date'      => $slotEnd->format('d.m.Y'),
//                 'mining_status' => 'Inactive',
//             ];
//             $cycleCount++;
//             $cursor = $slotEnd->copy();
//         }
//     }

//     usort($data, function ($a, $b) {
//         return Carbon::createFromFormat('d.m.Y', $a['start_date'])
//             ->lt(Carbon::createFromFormat('d.m.Y', $b['start_date'])) ? -1 : 1;
//     });

//     $final    = [];
//     $seenKeys = [];
//     foreach ($data as $item) {
//         $key = $item['coin'] . '_' . $item['start_date'];
//         if (!isset($seenKeys[$key])) {
//             $seenKeys[$key] = true;
//             $final[]        = $item;
//         }
//     }

//     return response()->json([
//         'status'  => true,
//         'message' => 'Mining history fetched successfully',
//         'data'    => $final
//     ]);
// }


// for future



// future

public function miningHistory(Request $request)
{
    $user = Auth::user();
    if (!$user) {
        return response()->json(['status' => false, 'message' => 'Unauthenticated'], 401);
    }

    $histories = DB::table('user_mining_history')
        ->where('user_id', $user->id)
        ->orderBy('start_date', 'asc')
        ->get();

    if ($histories->isEmpty()) {
        return response()->json(['status' => false, 'message' => 'Mining not started'], 404);
    }

    $userMining = DB::table('user_mining')->where('user_id', $user->id)->first();
    $inactiveMonths = $userMining ? (int)$userMining->inactive_months : 0;
    $currentMonth   = $userMining ? (int)$userMining->current_month : 0;

    $firstCoin = Coin::where('name', $histories->first()->coin_type)->first();
    $maxCycles = (int)($firstCoin ? $firstCoin->mining_period : 24);

    $planEnd       = Carbon::parse($histories->last()->end_date);
    $coinHistories = $histories->values();
    $total         = $coinHistories->count();

    $data             = [];
    $activeCycleCount = 0; // ✅ Only active cycles count toward the cap
    $cycleCount       = 0; // total safety counter

    for ($i = 0; $i < $total; $i++) {
        if ($activeCycleCount >= $maxCycles) break; // ✅ Only break on active cycle cap

        $row        = $coinHistories[$i];
        $cycleStart = Carbon::parse($row->start_date);

        $thisDue  = Carbon::parse($row->monthly_due_date);
        $cycleEnd = $thisDue->copy();
        if ($cycleEnd->gt($planEnd)) $cycleEnd = $planEnd->copy();

        $nextStart = ($i + 1 < $total)
            ? Carbon::parse($coinHistories[$i + 1]->start_date)
            : null;

        // ✅ Add active row
        $data[] = [
            'coin'          => $row->coin_type,
            'start_date'    => $cycleStart->format('d.m.Y'),
            'end_date'      => $cycleEnd->format('d.m.Y'),
            'mining_status' => 'Active',
        ];
        $activeCycleCount++; // ✅ Increment ONLY for active
        $cycleCount++;

        // ✅ Fill gap (inactive) between this row's due date and next row's start
        if ($nextStart && $thisDue->lt($nextStart)) {
            $gapCursor = $thisDue->copy();

            while ($gapCursor->lt($nextStart)) {
                $slotEnd  = $gapCursor->copy()->addDays(30);
                if ($slotEnd->gt($nextStart)) $slotEnd = $nextStart->copy();

                $slotDays = $gapCursor->diffInDays($slotEnd);
                if ($slotDays < 28) break;

                $data[] = [
                    'coin'          => $row->coin_type,
                    'start_date'    => $gapCursor->format('d.m.Y'),
                    'end_date'      => $slotEnd->format('d.m.Y'),
                    'mining_status' => 'Inactive',
                ];
                // ✅ Inactive does NOT increment $activeCycleCount
                $cycleCount++;
                $gapCursor = $slotEnd->copy();
            }
        }
    }

    // ✅ Append remaining inactive months after last active row
    $generatedInactive = count(array_filter($data, fn($d) => $d['mining_status'] === 'Inactive'));

    if ($inactiveMonths > 0 && $generatedInactive < $inactiveMonths && !empty($data)) {
        // ✅ Find the last ACTIVE entry specifically
        $activeEntries = array_filter($data, fn($d) => $d['mining_status'] === 'Active');
        $lastActive    = end($activeEntries);
        $cursor        = Carbon::createFromFormat('d.m.Y', $lastActive['end_date']);
        $remaining     = $inactiveMonths - $generatedInactive;

        for ($m = 0; $m < $remaining; $m++) { // ✅ No cycle cap on inactive months
            $slotEnd = $cursor->copy()->addDays(30);
            $data[]  = [
                'coin'          => $lastActive['coin'],
                'start_date'    => $cursor->format('d.m.Y'),
                'end_date'      => $slotEnd->format('d.m.Y'),
                'mining_status' => 'Inactive',
            ];
            $cursor = $slotEnd->copy();
        }
    }

    // Sort by start_date ascending
    usort($data, function ($a, $b) {
        return Carbon::createFromFormat('d.m.Y', $a['start_date'])
            ->lt(Carbon::createFromFormat('d.m.Y', $b['start_date'])) ? -1 : 1;
    });

    // Deduplicate by coin + start_date
    $final    = [];
    $seenKeys = [];
    foreach ($data as $item) {
        $key = $item['coin'] . '_' . $item['start_date'];
        if (!isset($seenKeys[$key])) {
            $seenKeys[$key] = true;
            $final[]        = $item;
        }
    }

    return response()->json([
        'status'  => true,
        'message' => 'Mining history fetched successfully',
        'data'    => $final
    ]);
}







// public function updateCoin(Request $request)
// {
//     $userId = $request->user_id;
//     $newCoinType = $request->coin_type;

//     $mining = UserMining::where('user_id', $userId)->first();

//     if (!$mining) {
//         return response()->json([
//             'message' => 'Mining record not found'
//         ], 404);
//     }

//     if (!$mining->start_date) {
//         return response()->json([
//             'message' => 'Mining not started yet'
//         ], 400);
//     }

//     $startDate = Carbon::parse($mining->start_date);

//     // ✅ Correct completed months
//     $monthsCompleted = $startDate->diffInMonths(now()) - $mining->inactive_months;
//     $monthsCompleted = max(0, $monthsCompleted);

//     // ✅ new duration
//     $newDuration = $this->getCoinDuration($newCoinType);

//     // ✅ progress calculation
//     if ($monthsCompleted >= $newDuration) {
//         $progress = 100;
//     } else {
//         $progress = ($monthsCompleted / $newDuration) * 100;
//     }

//     // ✅ new end date
//     $newEndDate = $startDate->copy()->addMonths($newDuration + $mining->inactive_months);

//     $mining->update([
//         'coin_type' => $newCoinType,
//         'progress' => min(100, $progress),
//         'end_date' => $newEndDate
//     ]);

//     return response()->json([
//         'message' => 'Coin updated successfully',
//         'new_coin' => $newCoinType,
//         'progress' => round($progress, 2),
//         'end_date' => $newEndDate
//     ]);
// }



public function updateCoin(Request $request)
    {
        $userId = $request->user_id;
        $newCoinType = $request->coin_type;

        $mining = UserMining::where('user_id', $userId)->first();

        if (!$mining) {
            return response()->json(['message' => 'Mining record not found'], 404);
        }

        if (!$mining->start_date) {
            return response()->json(['message' => 'Mining not started yet'], 400);
        }

        $startDate = Carbon::parse($mining->start_date);

        // ✅ completed months (inactive removed)
        $monthsCompleted = $startDate->diffInMonths(now()) - $mining->inactive_months;
        $monthsCompleted = max(0, $monthsCompleted);

        // ✅ new duration from DB
        $coin = Coin::where('name', $newCoinType)->first();
        $newDuration = $coin ? $coin->mining_period : 24;

        // ✅ progress
        if ($monthsCompleted >= $newDuration) {
            $progress = 100;
        } else {
            $progress = ($monthsCompleted / $newDuration) * 100;
        }

        $newEndDate = $startDate->copy()->addMonths($newDuration + $mining->inactive_months);

        $mining->update([
            'coin_type' => $newCoinType,
            'progress' => min(100, $progress),
            'end_date' => $newEndDate
        ]);

        return response()->json([
            'message' => 'Coin updated successfully',
            'new_coin' => $newCoinType,
            'progress' => round($progress, 2),
            'end_date' => $newEndDate
        ]);
    }
    
    
    
    public function sendDueNotifications()
{
    $today = Carbon::today();

    $users = User::whereNotNull('monthly_due_date')->get();

    foreach ($users as $user) {

        $dueDate = Carbon::parse($user->monthly_due_date);

        // Check if today is within 3 days before due date
        if ($today->between($dueDate->copy()->subDays(2), $dueDate)) {

            // 🔔 Send Notification
            // Example: DB / SMS / Email

            \Log::info("Notification sent to User ID: " . $user->id);

            // Example DB Notification
            // Notification::send($user, new DueReminderNotification());

        }
    }

    return response()->json(['message' => 'Notifications processed']);
}
    
    
    public function getNotifications()
{
    $notifications = \DB::table('notifications')
        ->where('user_id', auth()->id())
        ->orderBy('id', 'desc')
        ->get();

    return response()->json([
        'status' => true,
        'data' => $notifications
    ]);
}
    
    
    
}


