<?php namespace Digbang\L4Backoffice\Auth;

use Cartalyst\Sentry\Users\UserAlreadyActivatedException;
use Digbang\Security\Auth\AccessControl;
use Digbang\Security\Auth\Emailer;
use Illuminate\Container\Container;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Redirector;
use Illuminate\Support\MessageBag;
use Cartalyst\Sentry\Users\LoginRequiredException;
use Cartalyst\Sentry\Users\PasswordRequiredException;
use Cartalyst\Sentry\Users\UserNotActivatedException;
use Cartalyst\Sentry\Users\UserNotFoundException;
use Cartalyst\Sentry\Users\WrongPasswordException;
use Cartalyst\Sentry\Sentry;

class AuthController extends Controller
{
	protected $layout = 'l4-backoffice::layouts.default';
	protected $sentry;
	protected $redirector;
	protected $accessControl;
	protected $emailer;

	function __construct(Redirector $redirector, AccessControl $accessControl, Emailer $emailer, Container $app)
	{
		$this->sentry = $app['sentry'];
		$this->redirector = $redirector;
		$this->accessControl = $accessControl;
		$this->emailer = $emailer;
	}

	public function login()
	{
		return \View::make('l4-backoffice::auth.login', [
			'errors' => \Session::has('errors') ? \Session::get('errors') : new MessageBag()
		]);
	}

	public function authenticate()
	{
		$errors = new MessageBag();
		try
		{
			$this->accessControl->authenticate(\Input::get('email'), \Input::get('password'), \Input::get('remember'));

			return $this->redirector->intended(route('backoffice.index'));
		}
		catch (LoginRequiredException $e)
		{
			$errors->add('email', \Lang::get('l4-backoffice::auth.validation.login-required'));
		}
		catch (PasswordRequiredException $e)
		{
			$errors->add('password', \Lang::get('l4-backoffice::auth.validation.password.required'));
		}
		catch (WrongPasswordException $e)
		{
			$errors->add('password', \Lang::get('l4-backoffice::auth.validation.password.wrong'));
		}
		catch (UserNotFoundException $e)
		{
			$errors->add('email', \Lang::get('l4-backoffice::auth.validation.user.not-found'));
		}
		catch (UserNotActivatedException $e)
		{
			$errors->add('email', \Lang::get('l4-backoffice::auth.validation.user.not-activated'));
		}
		
		return $this->redirector->route('backoffice.auth.login')->withInput()->withErrors($errors);
	}

	public function logout()
	{
		$this->accessControl->logout();

		return $this->redirector->route('backoffice.auth.login');
	}

	public function activate($activationCode)
	{
		try
		{
			if ($this->accessControl->activate($activationCode))
			{
				return $this->redirector->route('backoffice.auth.login')
					->with([
						'success' => \Lang::get('l4-backoffice::auth.activation.success')
					]);
			}

			return \View::make('l4-backoffice::auth.activation-expired', [
				'email' => \Config::get('l4-backoffice::auth.contact')
			]);
		}
		catch (UserNotFoundException $e)
		{
			return $this->redirector->route('backoffice.auth.login')
				->with(['danger' => \Lang::get('l4-backoffice::auth.validation.user.not-found')]);
		}
		catch (UserAlreadyActivatedException $e)
		{
			return $this->redirector->route('backoffice.auth.login')
				->with(['warning' => \Lang::get('l4-backoffice::auth.validation.user.already-active')]);
		}
	}

	public function resetPassword($id, $resetCode)
	{
		if ($this->accessControl->checkResetPasswordCode($id, $resetCode))
		{
			return \View::make('l4-backoffice::auth.reset-password', [
				'id'        => $id,
				'resetCode' => $resetCode
			]);
		}

		return $this->redirector->route('backoffice.auth.login')->with('danger', \Lang::get('l4-backoffice::auth.validation.reset-password.incorrect'));
	}

	public function resetPasswordRequest($id)
	{
		$input = \Input::only(['password', 'password_confirmation', 'id']);
		$input['url-id'] = $id;

		$validator = \Validator::make($input, [
			'password' => 'required|confirmed',
			'id' => 'same:url-id'
		]);

		if ($validator->fails())
		{
			return $this->redirector->back()->withErrors($validator->errors());
		}

		try
		{
			if ($this->accessControl->resetPassword(\Input::get('reset_password_code'), $input['password']))
			{
				return $this->redirector->route('backoffice.auth.login')
					->with('success', \Lang::get('l4-backoffice::auth.reset-password.success'));
			}

			return $this->redirector->route('backoffice.auth.login')
				->with('danger', \Lang::get('l4-backoffice::auth.validation.reset-password.incorrect'));
		}
		catch (UserNotFoundException $e)
		{
			return $this->redirector->route('backoffice.auth.login')
				->with('danger', \Lang::get('l4-backoffice::auth.validation.user.not-found'));
		}
	}

	public function forgotPassword()
	{
		return \View::make('l4-backoffice::auth.request-reset-password');
	}

	public function forgotPasswordRequest()
	{
		try
		{
			$email = trim(\Input::get('email'));
			if (!$email)
			{
				throw new UserNotFoundException(\Lang::get('l4-backoffice::auth.validation.reset-password.email'));
			}

			/* @var $user \Cartalyst\Sentry\Users\Eloquent\User */
			$user = $this->sentry->findUserByLogin(\Input::get('email'));

			$this->emailer->sendPasswordReset(
				$user,
				route('backoffice.auth.password.reset', [$user->getId(), $user->getResetPasswordCode()])
			);

			return $this->redirector->route('backoffice.auth.login')
				->with('info', \Lang::get('l4-backoffice::auth.reset-password.email-sent', ['email' => $user->email]));
		}
		catch (UserNotFoundException $e)
		{
			return $this->redirector->back()->withErrors(['email' => \Lang::get('l4-backoffice::auth.validation.user.not-found')]);
		}
	}
}