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
        <link href="/css/shakys.webflow.css" rel="stylesheet" type="text/css">
        <link href="/css/users.css" rel="stylesheet" type="text/css">
        <link href="/css/webflow.css" rel="stylesheet" type="text/css">
        <!-- [if lt IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js" type="text/javascript"></script><![endif] -->
        <script type="text/javascript">!function(o,c){var n=c.documentElement,t=" w-mod-";n.className+=t+"js",("ontouchstart"in o||o.DocumentTouch&&c instanceof DocumentTouch)&&(n.className+=t+"touch")}(window,document);</script>
        <link href="images/favicon.ico" rel="shortcut icon" type="image/x-icon">
        <link href="images/webclip.png" rel="apple-touch-icon">

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <style>
            /*! normalize.css v8.0.1 | MIT License | github.com/necolas/normalize.css */html{line-height:1.15;-webkit-text-size-adjust:100%}body{margin:0}a{background-color:transparent}[hidden]{display:none}html{font-family:system-ui,-apple-system,BlinkMacSystemFont,Segoe UI,Roboto,Helvetica Neue,Arial,Noto Sans,sans-serif,Apple Color Emoji,Segoe UI Emoji,Segoe UI Symbol,Noto Color Emoji;line-height:1.5}*,:after,:before{box-sizing:border-box;border:0 solid #e2e8f0}a{color:inherit;text-decoration:inherit}svg,video{display:block;}video{max-width:100%;height:auto}.bg-white{--bg-opacity:1;background-color:#fff;background-color:rgba(255,255,255,var(--bg-opacity))}.bg-gray-100{--bg-opacity:1;background-color:#f7fafc;background-color:rgba(247,250,252,var(--bg-opacity))}.border-gray-200{--border-opacity:1;border-color:#edf2f7;border-color:rgba(237,242,247,var(--border-opacity))}.border-t{border-top-width:1px}.flex{display:flex}.grid{display:grid}.hidden{display:none}.items-center{align-items:center}.justify-center{justify-content:center}.font-semibold{font-weight:600}.h-5{height:1.25rem}.h-8{height:2rem}.h-16{height:4rem}.text-sm{font-size:.875rem}.text-lg{font-size:1.125rem}.leading-7{line-height:1.75rem}.mx-auto{margin-left:auto;margin-right:auto}.ml-1{margin-left:.25rem}.mt-2{margin-top:.5rem}.mr-2{margin-right:.5rem}.ml-2{margin-left:.5rem}.mt-4{margin-top:1rem}.ml-4{margin-left:1rem}.mt-8{margin-top:2rem}.ml-12{margin-left:3rem}.-mt-px{margin-top:-1px}.max-w-6xl{max-width:72rem}.min-h-screen{min-height:100vh}.overflow-hidden{overflow:hidden}.p-6{padding:1.5rem}.py-4{padding-top:1rem;padding-bottom:1rem}.px-6{padding-left:1.5rem;padding-right:1.5rem}.pt-8{padding-top:2rem}.fixed{position:fixed}.relative{position:relative}.top-0{top:0}.right-0{right:0}.shadow{box-shadow:0 1px 3px 0 rgba(0,0,0,.1),0 1px 2px 0 rgba(0,0,0,.06)}.text-center{text-align:center}.text-gray-200{--text-opacity:1;color:#edf2f7;color:rgba(237,242,247,var(--text-opacity))}.text-gray-300{--text-opacity:1;color:#e2e8f0;color:rgba(226,232,240,var(--text-opacity))}.text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}.text-gray-500{--text-opacity:1;color:#a0aec0;color:rgba(160,174,192,var(--text-opacity))}.text-gray-600{--text-opacity:1;color:#718096;color:rgba(113,128,150,var(--text-opacity))}.text-gray-700{--text-opacity:1;color:#4a5568;color:rgba(74,85,104,var(--text-opacity))}.text-gray-900{--text-opacity:1;color:#1a202c;color:rgba(26,32,44,var(--text-opacity))}.underline{text-decoration:underline}.antialiased{-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale}.w-5{width:1.25rem}.w-8{width:2rem}.w-auto{width:auto}.grid-cols-1{grid-template-columns:repeat(1,minmax(0,1fr))}@media (min-width:640px){.sm\:rounded-lg{border-radius:.5rem}.sm\:block{display:block}.sm\:items-center{align-items:center}.sm\:justify-start{justify-content:flex-start}.sm\:justify-between{justify-content:space-between}.sm\:h-20{height:5rem}.sm\:ml-0{margin-left:0}.sm\:px-6{padding-left:1.5rem;padding-right:1.5rem}.sm\:pt-0{padding-top:0}.sm\:text-left{text-align:left}.sm\:text-right{text-align:right}}@media (min-width:768px){.md\:border-t-0{border-top-width:0}.md\:border-l{border-left-width:1px}.md\:grid-cols-2{grid-template-columns:repeat(2,minmax(0,1fr))}}@media (min-width:1024px){.lg\:px-8{padding-left:2rem;padding-right:2rem}}@media (prefers-color-scheme:dark){.dark\:bg-gray-800{--bg-opacity:1;background-color:#2d3748;background-color:rgba(45,55,72,var(--bg-opacity))}.dark\:bg-gray-900{--bg-opacity:1;background-color:#1a202c;background-color:rgba(26,32,44,var(--bg-opacity))}.dark\:border-gray-700{--border-opacity:1;border-color:#4a5568;border-color:rgba(74,85,104,var(--border-opacity))}.dark\:text-white{--text-opacity:1;color:#fff;color:rgba(255,255,255,var(--text-opacity))}.dark\:text-gray-400{--text-opacity:1;color:#cbd5e0;color:rgba(203,213,224,var(--text-opacity))}}
        </style>

        <style>
            /*
            body {
                font-family: 'Nunito', sans-serif;
            }
            */
        </style>
      <meta name="format-detection" content="telephone=no">
      @endsection
    </head>
    <body class="antialiased bodyClass">
        @section('page_title', 'USERS')
        @section('content')

        <!-- HTML FOR THE NEW USER DATA ENTRY -->
        <div class="user_add_section">
            <div id="user_add_icon" class="user_add_icon_frame">
                <div class="all_products_search_frame">
                    <input id="user_search_text" type="text" class="all_products_search_bar" placeholder="Search text or Enter for all">
                    <div class="search_product_icon">
                        <img src="/images/MagnifierBk.png">
                        <input type="button" class="all_products_search_button" onclick="userSearchClick()">
                    </div>
                </div>

                <div id="user_add_icon" class="user_add_icon" onclick="userAddClick(this)">
                    <a>+</a>
                </div>
            </div>
            <div id="user_add_form" class="user_form_block" hidden>
                <!--form method="POST" action="{{ route('register') }}" class="user_form"-->
                <form id="user_form" class="user_form">
                    @csrf
                    <div class="user_data_1">
                        <input id="add_user_username" type="text" class="user_username w-input" maxlength="256" name="username" data-name="name" placeholder="Username" :value="old('username')" required autofocus />
                    </div>
                    <div class="user_data_1">
                        <input id="add_user_name" type="text" class="user_name w-input" maxlength="256" name="name" data-name="name" placeholder="Name" id="name" :value="old('name')" required autofocus />
                        <input id="add_user_email" type="email" class="user_email w-input" maxlength="256" name="email" data-name="email" placeholder="Email" id="email" :value="old('email')"/>
                        <select id="add_user_type" class="user_type w-input" name="user_type" id="user_type" :value="old('user_type'>">
                        @auth
                        @if(Auth::user()->user_type == 'admin')
                            <option value="admin">admin</option>
                            <option value="user" selected>user</option>
                            <option value="pickup">pickup</option>
                        @else    
                            <option value="admin" selected>admin</option>
                            <option value="user">user</option>
                            <option value="pickup">pickup</option>
                        @endif
                        @endauth

                        @guest
                            <option value="admin" selected>admin</option>
                        @endguest

                        </select>
                    </div>
                    <div class="user_data_2">

                        <input id="add_user_password" type="password" class="user_password w-input" 
                                    maxlength="256" name="password" data-name="password" 
                                    placeholder="Password" id="password"
                                    required autocomplete="new-password" />
        
                        <input id="add_confirm_user_password" type="password" class="user_password_confirm w-input" 
                                    maxlength="256" name="password_confirmation" 
                                    data-name="password_confirmation" placeholder="Confirm Password" 
                                    id="password_confirmation" required />
                        <div class="user_add_button">
                            <button type="button" data-wait="Please wait..." class="user_button accept_button" onclick="userCreateClick(this)">
                                {{ __('Create') }}
                            </button>
                            <button type="button" data-wait="Please wait..." class="user_button discard_button" onclick="userAbortClick(this)">
                                {{ __('Abort') }}
                            </button>
                        </div>
                    </div>
                    <div id="create_message" class="create_message" hidden></div>
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
                            <div class="user_name">
                            <div class="user_name field_label">Name</div>
                            <input id="edited_name" type="text" class="w-input edited_user_name" maxlength="256" name="name" placeholder="Name" required>
                            </div>
                            <div class="user_email">
                                <div class="field_label">Email</div>
                                <input id="edited_email" type="email" class="edited_email w-input" maxlength="256" name="email" placeholder="Email">
                            </div>
                            <div class="user_type">
                                <div class="edited_user_type field_label">User Type</div>
                                <select id="edited_user_type" type="text" class="edited_user_type w-input" maxlength="256" name="user_type" placeholder="User Type" required>
                                    <option value="admin">admin</option>
                                    <option value="user">user</option>
                                    <option value="pickup">pickup</option>
                                </select>
                            </div>
                        </div>
                        <div class="user_data_2 center">
                            <div class="user_add_button">
                                <div style="display: block;">
                                    <input id="edited_user_save" type="button" value="SAVE" data-wait="Please wait..." class="add_user_button accept_button box_shadow w-button" onclick="saveUser(this)">
                                </div>    
                            </div>
                            <div class="user_add_button">
                                <input id="edited_user_password" type="button" value="ABORT" data-wait="Please wait..." class="add_user_button discard_button box_shadow w-button" onclick="discard(this)">
                            </div>
                        </div>
                        <div id="user_edit_message" class="user_edit_message" hidden>
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
                <div id="password_change_result" class="password_change_result" hidden><span></span></div>
                <div class="user_password_section">
                    <input id="user_password" type="password" class="user_password w-input" maxlength="256" minlength="8" placeholder="New Password" required="">
                    <input id="user_password_confirm" type="password" class="user_password_confirm w-input" maxlength="256" placeholder="Confirm New Password" required="">
                </div>
                <div class="user_password_section">
                    <input id="new_password" type="button" value="CREATE" data-wait="Please wait..." class="create_discard_passwd_button accept_button w-button" onclick="createPassword(this)">
                    <input id="confirm_new_password" type="button" value="DISCARD" data-wait="Please wait..." class="create_discard_passwd_button discard_button w-button" onclick="discardPassword(this)">
                </div>
            </form>    
        </div>
 
        <!-- HTML TO RESTORE USER DATA DISPLAY AFTER EDITED DATA IS SAVED -->    
        <!-- THIS CONTENT WILL BE SHOWN FROM JAVASCRIPT WHEN THE USER SAVES THE EDITED USER DATA -->    
        <div id="user_data" hidden>
            <div id="user_edit_wrap">
                <div id="user_edit_frame">    
                    <div class="user_sections_wraper">
                        <div class="user_data_wraper">
                            <div class="user_section horizontal">
                                <div class="user_edit_section">
                                    <div class="user_field_header">USERNAME</div>
                                    <div id="user_username" class="user_field_content shadowRight">user_username</div>
                                </div>
                                <div class="user_edit_section">
                                    <div class="user_field_header">NAME</div>
                                    <div id="user_name" class="user_field_content shadowRight">user_name</div>
                                </div>
                            </div>
                            <div class="user_section horizontal">
                                <div class="user_edit_section">
                                    <div class="user_field_header">EMAIL</div>
                                    <div id="user_email" class="user_field_content shadowRight">user_email</div>
                                </div>
                                    <div class="user_edit_section two">
                                    <div class="user_field_header">USER TYPE</div>
                                    <div id="user_type" class="user_field_content shadowRight">user_type</div>
                                </div>
                            </div>
                        </div>
                        <div class="user_section horizontal user_buttons_wraper">
                            <div class="user_edit_section center" id="edit_buttons">
                                <input type="button" id="edit_user" class="add_user_button edit_button box_shadow edit w-button" value="EDIT" onclick="edit('user_id', this)">
                                <input type="button" id="delete_user" class="add_user_button delete_button box_shadow edit delete w-button" value="DELETE" onclick="deleteUser('user_id', this)">
                                <input type="button" id="new_user_password" class="add_user_button discard_button box_shadow edit password w-button" value="PASSWD" onclick="newPassword('user_id')">
                            </div>
                        </div>
                    </div>
                    <div id="user_edit_action_result" class="user_edit_action_result" hidden>
                    </div>
                </div>
                <div id="user_password_reset">

                </div>
            </div>
        </div>

        <!-- LIST OF REGISTERED USERS RECEIVED FROM THE VIEW REQUEST -->
        <div id="users_list">
            @foreach($users as $key => $user)
            <div id="{{$user->id}}" class="user_div">
                <div id="user_edit_wrap">
                    <div id="user_edit_frame">
                        <div class="user_sections_wraper">
                            <div class="user_data_wraper">
                                <div class="user_section horizontal">
                                    <div class="user_edit_section">
                                        <div class="user_field_header">USERNAME</div>
                                        <div class="user_field_content shadowRight">{{$user->username}}</div>
                                    </div>
                                    <div class="user_edit_section">
                                        <div class="user_field_header">NAME</div>
                                        <div class="user_field_content shadowRight">{{$user->name}}</div>
                                    </div>
                                </div>
                                <div class="user_section horizontal">
                                    <div class="user_edit_section">
                                        <div class="user_field_header">EMAIL</div>
                                        <div class="user_field_content shadowRight">{{$user->email}}</div>
                                    </div>
                                    <div class="user_edit_section two">
                                        <div class="user_field_header">USER TYPE</div>
                                        <div class="user_field_content shadowRight">{{$user->user_type}}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="user_section horizontal user_buttons_wraper">
                                <div class="user_edit_section center" id="edit_buttons">
                                    <input type="button" id="edit_user" class="add_user_button edit_button box_shadow edit w-button" value="EDIT" onclick="edit('{{$user->id}}', this)">
                                    <input type="button" id="delete_user" class="add_user_button delete_button box_shadow edit delete w-button" value="DELETE" onclick="deleteUser('{{$user->id}}', this)">
                                    <input type="button" id="new_user_password" class="add_user_button discard_button box_shadow edit password w-button" value="PASSWD" onclick="newPassword('{{$user->id}}')">
                                </div>
                            </div>
                        </div>
                        <div id="user_edit_action_result" class="user_edit_action_result" hidden>
                            action_result_message
                        </div>
                    </div>
                    <div id="user_password_reset">

                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- HTML TO BE ADDED TO THE USERS LIST WHEN A NEW USER IS CREATED -->
        <div id="added_user_html" hidden>
            <div id="user-id" class="user_div">
                <div id="user_edit_wrap">
                    <div id="user_edit_frame">
                        <div class="user_sections_wraper">
                            <div class="user_data_wraper">
                                <div class="user_section horizontal">
                                    <div class="user_edit_section">
                                        <div class="user_field_header">USERNAME</div>
                                        <div class="user_field_content shadowRight">user-username</div>
                                    </div>
                                    <div class="user_edit_section">
                                        <div class="user_field_header">NAME</div>
                                        <div class="user_field_content shadowRight">user-name</div>
                                    </div>
                                </div>
                                <div class="user_section horizontal">
                                    <div class="user_edit_section">
                                        <div class="user_field_header">EMAIL</div>
                                        <div class="user_field_content shadowRight">user-email</div>
                                    </div>
                                    <div class="user_edit_section two">
                                        <div class="user_field_header">USER TYPE</div>
                                        <div class="user_field_content shadowRight">user-type</div>
                                    </div>
                                </div>
                            </div>
                            <div class="user_section horizontal user_buttons_wraper">
                                <div class="user_edit_section center" id="edit_buttons">
                                    <input type="button" id="edit_user" class="add_user_button edit_button box_shadow edit w-button" value="EDIT" onclick="edit('user-id', this)">
                                    <input type="button" id="delete_user" class="add_user_button delete_button box_shadow edit delete w-button" value="DELETE" onclick="deleteUser('user-id', this)">
                                    <input type="button" id="new_user_password" class="add_user_button discard_button box_shadow edit password w-button" value="PASSWD" onclick="newPassword('user-id')">
                                </div>
                            </div>
                        </div>
                        <div id="user_edit_action_result" class="user_edit_action_result" hidden>
                        </div>
                    </div>
                    <div id="user_password_reset">

                    </div>
                </div>
            </div>
        </div>

        <script src="https://d3e54v103j8qbb.cloudfront.net/js/jquery-3.5.1.min.dc5e7f18c8.js?site=604d41d40c813292693d08e7" type="text/javascript" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
        <script src="/js/webflow.js" type="text/javascript"></script>
        <script src="/js/users.js" type="text/javascript"></script>
        <script src="/js/garcellLib.js" type="text/javascript"></script>
        <!-- [if lte IE 9]><script src="https://cdnjs.cloudflare.com/ajax/libs/placeholders/3.0.2/placeholders.min.js"></script><![endif] -->
        @endsection    
    </body>
</html>
