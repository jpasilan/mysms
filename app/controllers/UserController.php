<?php

class UserController extends BaseController {
    
  public function showLogin()
  {
    return View::make('users.login');
  }
  
  public function login()
  {
    $message = null;
    
    try {
      Sentry::authenticate(Input::only('email', 'password'), false);

      return Redirect::to('dashboard');
    } catch (Cartalyst\Sentry\Users\UserNotActivatedException $e) {
      $message = ['danger' => 'User not activated yet.'];
    } catch (Exception $e) {
      $message = ['danger' => 'Invalid email or password'];
    }

    return $message
      ? Redirect::to('/')->withMessage($message)
      : Redirect::to('/');
  }
  
  public function logout()
  {
    Sentry::logout();
    
    return Redirect::to('/');
  }
  
  public function showDashboard()
  {
    return View::make('users.dashboard', ['user' => Sentry::getUser()]);
  }
  
}