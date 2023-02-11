<?php

namespace Modules\User\Http\Controllers\Auth;

use Illuminate\Support\Facades\Storage;
use Modules\User\Http\Controllers\Controller;
use Modules\User\Entities\User;
use Modules\User\Notifications\UserRegistered;
use Illuminate\Support\Facades\Auth;

use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Modules\Events\Entities\EventCategory;
use Modules\Events\Entities\Event;
use Modules\Events\Entities\Guest;
use Modules\Events\Entities\SubGuest;
use Modules\Upsell\Entities\Upsell;


class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
     */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    public function redirectTo(){

        $this->redirectTo = 'dashboard';

        switch (Auth::user()->role) {
            case 'user':
                $this->redirectTo = 'dashboard';
                break;
            case 'admin':
                $this->redirectTo = 'settings';
                break;
            default:
                $this->redirectTo = 'dashboard';
                break;
        }
        return $this->redirectTo;

    }
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $rules = [
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ];
        $secret = config('recaptcha.api_secret_key');
        $site_key = config('recaptcha.api_site_key');
        
        if ($secret && $site_key) {
            $rules['g-recaptcha-response'] = 'recaptcha';
        }
        return Validator::make($data, $rules);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $data['role'] = 'user';
        $user = User::create([
               'name'          => $data['name'],
               'email'         => $data['email'],
               'role'          => $data['role'],
               'password'      => Hash::make($data['password']),
        ]);
        $user->notify((new UserRegistered())->onQueue('mail'));

        return $user; 
        
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        
        return view('themes::' . config('app.SITE_LANDING') . '.auth.register');
    }

    public function registered(Request $request, $user)
    {
        $user->update(['login_nums' =>  $user->login_nums + 1]);
        // create category
        $category = EventCategory::create([
            'name' => 'Test Category',
            'user_id' => $user->id,
            'type' => $user->role
        ]);
        // create event
        $start_date = Carbon::now()->addDays(10);
        $end_date = Carbon::now()->addYears(10);

        $event = Event::create([
            'name' => 'Test Event',
            'category_id' => $category->id,
            'short_slug' => $user->id . time(),
            'user_id' => $user->id,
            'register_end_date' => $start_date,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'is_test' => 1,
            'type' => 'OFFLINE',
            'quantity' => -1,
            'tagline' => 'tagline',
            'description' => 'description',
            'noti_register_success' => 'Registration successful!',
            'font_family' => 'Open Sans',
            'info_items' => [],
            'ticket_currency' => 'USD',
            'ticket_items' => ['name' => ['Admission'], 'price' => ['5'], 'description' => ['Admission']],
            'email_content' => config('events.EVENT_EMAIL_CONTENT'),
            'email_sender_name' => $user->name,
            'email_sender_email' => $user->email,
            'email_subject' => 'Registration event successful!'
        ]);
        // create guest
        $guest = Guest::create([
            'user_id' => $event->user_id,
            'event_id' => $event->id,
            'fullname' => "John Doe",
            'email' => "johndoe@gmail.com",
            'birthday' => "1980-01-01",
            'phone' => "11111111111",
            'info_items' => $event->info_items,
            'ticket_name' => $event->ticket_items["name"][0],
            'ticket_price' => $event->ticket_items["price"][0],
            'ticket_currency' => $event->ticket_currency
        ]);
        SubGuest::create([
            "guest_id" => $guest->id,
            "fullname" => "Mary Moe",
            "email" => "marymoe@gmail.com",
            "birthday" => "1980-01-02",
            "phone" => "11111111112"
        ]);
        $upsell_img_name = "upsell_item_" . uniqid() . ".jpg";
        Storage::disk("public")->copy("upsells/upsell_item_1.jpg", "upsells/" . $upsell_img_name);
        Upsell::create([
            "user_id" => $user->id,
            "title" => "Portable handgun",
            "price" => [5, 10, 15],
            "image" => "upsells/" . $upsell_img_name,
            "description" => "<p>Portable handgun description.</p>"
        ]);
        return redirect()->intended($this->redirectPath());
    }
}
