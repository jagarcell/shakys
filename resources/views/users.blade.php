@extends('layouts.app')
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-wf-page="605538017156323a2f5ab124" data-wf-site="604d41d40c813292693d08e7">
    <head>
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        @section('title', 'Users')

        @section('headsection')
        <link href="css/shakys.webflow.css" rel="stylesheet" type="text/css">
        <link href="css/users.css" rel="stylesheet" type="text/css">
        <link href="css/webflow.css" rel="stylesheet" type="text/css">
        <!-- [if lt IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js" type="text/javascript"></script><![endif] -->
        <script type="text/javascript">!function(o,c){var n=c.documentElement,t=" w-mod-";n.className+=t+"js",("ontouchstart"in o||o.DocumentTouch&&c instanceof DocumentTouch)&&(n.className+=t+"touch")}(window,document);</script>
        <link href="images/favicon.ico" rel="shortcut icon" type="image/x-icon">
        <link href="images/webclip.png" rel="apple-touch-icon">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <style>
            /*! normalize.css v8.0.1 | MIT License | github.com/necolas/normalize.css */html{line-height:1.15;-webkit-text-size-adjust:100%}body{margin:0}a{background-color:transparent}[hidden]{display:none}html{font-family:system-ui,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,Noto Sans,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol,Noto Color Emoji;line-height:1.5}*,:after,:before{box-sizing:border-box;border:0 solid #e2e8f0}a{color:inherit;text-decoration:inherit}svg,video{display:block;vertical-align:middle}video{max-width:100%;height:auto}.bg-white{--bg-opacity:1;background-color:#fff;background-color:rgba(255,255,255,var(--bg-opacity))}.bg-gray-100{--bg-opacity:1;background-color:#f7fafc;background-color:rgba(247,250,252,var(--bg-opacity))}.border-gray-200{--border-opacity:1;border-color:#edf2f7;border-color:rgba(237,242,247,var(--border-opacity))}.border-t{border-top-width:1px}.flex{display:flex}.grid{display:grid}.hidden{display:none}.items-center{align-items:center}.justify-center{justify-content:center}.font-semibold{font-weight:600}.h-5{height:1.25rem}.h-8{height:2rem}.h-16{height:4rem}.text-sm{font-size:.875rem}.text-lg{font-size:1.125rem}.leading-7{line-height:1.75rem}.mx-auto{margin-left:auto;margin-right:auto}.ml-1{margin-left:.25rem}.mt-2{margin-top:.5rem}.mr-2{margin-right:.5rem}.ml-2{margin-left:.5rem}.mt-4{margin-top:1rem}.ml-4{margin-left:1rem}.mt-8{margin-top:2rem}.ml-12{margin-left:3rem}.-mt-px{margin-top:-1px}.max-w-6xl{max-width:72rem}.min-h-screen{min-height:100vh}.overflow-hidden{overflow:hidden}.p-6{padding:1.5rem}.py-4{padding-top:1rem;padding-bottom:1rem}.px-6{padding-left:1.5rem;padding-right:1.5rem}.pt-8{padding-top:2rem}.fixed{position:fixed}.relative{position:relative}.top-0{top:0}.right-0{right:0}.shadow{box-shadow:0 1px 3px 0 rgba(0,0,0,.1),0 1px 2px 0 rgba(0,0,0,.06)}.text-center{text-align:center}.text-gray-200{--text-opacity:1;color:#edf2f7;color:rgba(237,242,247,var(--text-opacity))}.text-gray-300{--text-opacity:1;color:#e2e8f0;color:rgba(226,232,240,var(--text-opacity))}.text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}.text-gray-500{--text-opacity:1;color:#a0aec0;color:rgba(160,174,192,var(--text-opacity))}.text-gray-600{--text-opacity:1;color:#718096;color:rgba(113,128,150,var(--text-opacity))}.text-gray-700{--text-opacity:1;color:#4a5568;color:rgba(74,85,104,var(--text-opacity))}.text-gray-900{--text-opacity:1;color:#1a202c;color:rgba(26,32,44,var(--text-opacity))}.underline{text-decoration:underline}.antialiased{-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.w-5{width:1.25rem}.w-8{width:2rem}.w-auto{width:auto}.grid-cols-1{grid-template-columns:repeat(1,minmax(0,1fr))}@media (min-width:640px){.sm\:rounded-lg{border-radius:.5rem}.sm\:block{display:block}.sm\:items-center{align-items:center}.sm\:justify-start{justify-content:flex-start}.sm\:justify-between{justify-content:space-between}.sm\:h-20{height:5rem}.sm\:ml-0{margin-left:0}.sm\:px-6{padding-left:1.5rem;padding-right:1.5rem}.sm\:pt-0{padding-top:0}.sm\:text-left{text-align:left}.sm\:text-right{text-align:right}}@media (min-width:768px){.md\:border-t-0{border-top-width:0}.md\:border-l{border-left-width:1px}.md\:grid-cols-2{grid-template-columns:repeat(2,minmax(0,1fr))}}@media (min-width:1024px){.lg\:px-8{padding-left:2rem;padding-right:2rem}}@media (prefers-color-scheme:dark){.dark\:bg-gray-800{--bg-opacity:1;background-color:#2d3748;background-color:rgba(45,55,72,var(--bg-opacity))}.dark\:bg-gray-900{--bg-opacity:1;background-color:#1a202c;background-color:rgba(26,32,44,var(--bg-opacity))}.dark\:border-gray-700{--border-opacity:1;border-color:#4a5568;border-color:rgba(74,85,104,var(--border-opacity))}.dark\:text-white{--text-opacity:1;color:#fff;color:rgba(255,255,255,var(--text-opacity))}.dark\:text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}}
        </style>

        <style>
            body {
                font-family: 'Nunito', sans-serif;
            }
        </style>
      <meta name="format-detection" content="telephone=no">
      @endsection
    </head>
    <body class="antialiased">
        @section('page_title', 'USERS')
        @section('content')

        <!-- HTML FOR THE NEW USER DATA ENTRY -->
        <div class="user_div user_section">
            <div class="user_form_block w-form">
                <form method="POST" action="{{ route('register') }}" class="user_form">
                    @csrf
                    <div class="user_data_1">
                        <x-input type="text" class="user_name w-input" maxlength="256" name="name" data-name="name" placeholder="Name" id="name" :value="old('name')" required autofocus />
                        <x-input type="email" class="user_email w-input" maxlength="256" name="email" data-name="email" placeholder="Email" id="email" :value="old('email')" required />
                        <select class="user_type w-input" name="user_type" id="user_type" :value="old('user_type'>">
                        @auth
                        @if(Auth::user()->user_type == 'admin')
                            <option value="admin">admin</option>
                            <option value="user" selected>user</option>
                        @else    
                            <option value="admin" selected>admin</option>
                            <option value="user">user</option>
                        @endif
                        @endauth

                        @guest
                            <option value="admin" selected>admin</option>
                            <option value="user">user</option>
                        @endguest

                        </select>
                    </div>
                    <div class="user_data_2">

                        <x-input type="password" class="user_password w-input" 
                                    maxlength="256" name="password" data-name="password" 
                                    placeholder="Password" id="password"
                                    required autocomplete="new-password" />
        
                        <x-input type="password" class="user_password_confirm w-input" 
                                    maxlength="256" name="password_confirmation" 
                                    data-name="password_confirmation" placeholder="Confirm Password" 
                                    id="password_confirmation" required />
                        <div class="user_add_button">
                            <x-button data-wait="Please wait..." class="add_user_button w-button">
                            {{ __('NEW') }}
                            </x-button>
                        </div>
                    </div>
                </form>

                <!-- Validation Errors -->
                <x-auth-validation-errors class="mb-4 errors" :errors="$errors" />
            </div>
        </div>

        <!-- HTML FOR THE EDITING OF USER DATA -->
        <!-- THIS CONTENT WILL BE SHOWN FROM JAVASCRIPT WHEN THE USER STARTS THE EDIT USER ACTION -->    
        <div  id="edit_section" hidden>    
            <div id="user_section" class="user_section">
                <div class="user_form_block w-form">
                    <form id="user_edit_form" name="email-form" data-name="Email Form" class="user_form">
                        @csrf
                        <div class="user_data_1">
                            <input id="edited_user_id" name="user_id" hidden>
                            <input id="edited_name" type="text" class="user_name w-input" maxlength="256" name="name" placeholder="Name" required>
                            <div class="user_email">
                                <input id="edited_email" type="email" class="edited_email w-input" maxlength="256" name="email" placeholder="Email" required>
                                <span id="email_error" class="user_email email_taken" hidden>THIS EMAIL IS ALREADY TAKEN!</span>
                            </div>
                            <div class="user_type">
                                <select id="edited_user_type" type="text" class="edited_user_type w-input" maxlength="256" name="user_type" placeholder="User Type" required>
                                    <option value="admin">admin</option>
                                    <option value="user">user</option>
                                </select>
                                <span id="edited_user_type_error" class="no_admin_left" hidden>THIS IS THE ONLY admin USER LEFT!<br>YOU CAN NOT CHANGE THE USER TYPE FROM admin</span>
                            </div>
                        </div>
                        <div class="user_data_2 center">
                            <!--input id="edited_password" type="password" class="user_password w-input" maxlength="256" name="Password-3" data-name="Password 3" placeholder="Password" required="">
                            <input id="edited_password_confirmation" type="password" class="user_password_confirm w-input" maxlength="256" name="Password-2" data-name="Password 2" placeholder="Password" required="" -->
                            <div class="user_add_button">
                                <div style="display: block;">
                                    <input id="edited_user_save" type="button" value="SAVE" data-wait="Please wait..." class="add_user_button w-button" onclick="saveUser(this)">
                                    <span id="save_session_expired" class="save_session_expired" hidden>Session expired! Please, refresh your browser.</span>
                                </div>    
                            </div>
                            <div class="user_add_button">
                                <input type="button" value="DISCARD" data-wait="Please wait..." class="add_user_button w-button" onclick="discard(this)">
                            </div>
                            <div class="user_add_button">
                                <input type="button" value="PASSWORD" data-wait="Please wait..." class="add_user_button w-button" onclick="password(this)">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- HTML FOR THE USER PASSWORD CHANGE -->
        <!-- THIS CONTENT WILL BE SHOWN FROM JAVASCRIPT WHEN THE USER CLICK ON PASSWORD BUTTON -->
        <div id="password_section" hidden>
            <form id="user_password_reset_form" class="user_password_reset">
                <input id="password_id" value='user_id' hidden>
                <div class="user_password_section">
                    <input id="user_password" type="password" class="user_password w-input" maxlength="256" minlength="8" placeholder="New Password" required="">
                    <input id="user_password_confirm" type="password" class="user_password_confirm w-input" maxlength="256" placeholder="Confirm New Password" required="">
                </div>
                <div class="user_password_section">
                    <input id="new_password" type="button" value="CREATE NEW PASSWORD" data-wait="Please wait..." class="create_discard_passwd_button w-button" onclick="createPassword(this)">
                    <input id="confirm_new_password" type="button" value="DISCARD NEW PASSWORD" data-wait="Please wait..." class="create_discard_passwd_button w-button" onclick="discardPassword(this)">
                </div>
                <div id="password_confirmation_missmatch" class="password_confirmation_missmatch" hidden><span>PASSWORD CONFIRMATION DOES NOT MATCH!</span></div>
                <div id="password_change_success" class="password_change_success" hidden><span>PASSWORD SUCCESFULLY CHANGED!</span></div>
            </form>    
        </div>
 
        <!-- HTML TO RESTORE USER DATA DISPLAY AFTER EDITED DATA IS SAVED -->    
        <!-- THIS CONTENT WILL BE SHOWN FROM JAVASCRIPT WHEN THE USER SAVES THE EDITED USER DATA -->    
        <div id="user_data" hidden>
            <div id="user_edit_wrap">
                <div id="user_edit_frame">    
                    <div class="user_section horizontal">
                        <div class="user_edit_section">
                            <div class="user_field_header">USER</div>
                            <div id="user_name" class="user_field_content">user_name</div>
                        </div>
                        <div class="user_edit_section">
                            <div class="user_field_header">EMAIL</div>
                            <div id="user_email" class="user_field_content">user_email</div>
                        </div>
                            <div class="user_edit_section two">
                            <div class="user_field_header">TYPE</div>
                            <div id="user_type" class="user_field_content user_type">user_type</div>
                        </div>
                        <div class="user_edit_section center" id="edit_buttons">
                            <a id="edit_user" class="add_user_button edit w-button" onclick="edit('user_id')">EDIT</a>
                            <a id="delete_user" class="add_user_button edit delete w-button" onclick="deleteUser('user_id')">DELETE</a>
                            <a id="new_user_password" class="add_user_button edit password w-button" onclick="newPassword('user_id')">PASSWD</a>
                        </div>
                    </div>
                    <div id="delete_user_error" class="delete_button_frame" hidden>
                        <span>NO MORE admin LEFT!<br>THIS USER CAN NOT BE DELETED!</span>
                    </div>
                    <div id="delete_user_success" class="delete_user_success" hidden>
                        <span>THIS USER HAS BEEN SUCCESSFULLY DELETED!</span>
                    </div>
                </div>
                <div id="user_password_reset">

                </div>
            </div>
        </div>

        <!-- LIST OF REGISTERED USERS RECEIVED FROM THE VIEW REQUEST -->
        @foreach($users as $key => $user)
        <div id="{{$user->id}}" class="user_div">
            <div id="user_edit_wrap">
                <div id="user_edit_frame">    
                    <div class="user_section horizontal">
                        <div class="user_edit_section">
                            <div class="user_field_header">USER</div>
                            <div class="user_field_content">{{$user->name}}</div>
                        </div>
                        <div class="user_edit_section">
                            <div class="user_field_header">EMAIL</div>
                            <div class="user_field_content">{{$user->email}}</div>
                        </div>
                        <div class="user_edit_section two">
                            <div class="user_field_header">TYPE</div>
                            <div class="user_field_content">{{$user->user_type}}</div>
                        </div>
                        <div class="user_edit_section center" id="edit_buttons">
                            <a id="edit_user" class="add_user_button edit w-button" onclick="edit('{{$user->id}}')">EDIT</a>
                            <a id="delete_user" class="add_user_button edit delete w-button" onclick="deleteUser('{{$user->id}}')">DELETE</a>
                            <a id="new_user_password" class="add_user_button edit password w-button" onclick="newPassword('{{$user->id}}')">PASSWD</a>
                        </div>
                    </div>
                    <div id="delete_user_error" class="delete_button_frame" hidden>
                        <span>NO MORE admin LEFT!<br>THIS USER CAN NOT BE DELETED!</span>
                    </div>
                    <div id="delete_user_success" class="delete_user_success" hidden>
                        <span>THIS USER HAS BEEN SUCCESSFULLY DELETED!</span>
                    </div>
                </div>
                <div id="user_password_reset">

                </div>
            </div>
        </div>
        @endforeach

        <script src="https://d3e54v103j8qbb.cloudfront.net/js/jquery-3.5.1.min.dc5e7f18c8.js?site=604d41d40c813292693d08e7" type="text/javascript" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
        <script src="js/webflow.js" type="text/javascript"></script>
        <script src="js/users.js" type="text/javascript"></script>
        <script src="js/garcellLib.js" type="text/javascript"></script>
        <!-- [if lte IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif] -->
        @endsection    
    </body>
</html>
