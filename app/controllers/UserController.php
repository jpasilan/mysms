<?php

class UserController extends BaseController {
  
  /**
   * Show login form.
   * 
   * @return View
   */
  public function showLogin()
  {
    return View::make('users.login');
  }
  
  /**
   * Authenticate user with the supplied email and password.
   * 
   * @return Redirect
   */
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
  
  /**
   * Logs out user.
   * 
   * return Redirect
   */
  public function logout()
  {
    Sentry::logout();
    
    return Redirect::to('/');
  }
  
  /**
   * Show the user dashboard.
   * 
   * @return View
   */
  public function showDashboard()
  {
    return View::make('users.dashboard', ['user' => Sentry::getUser()]);
  }
  
}