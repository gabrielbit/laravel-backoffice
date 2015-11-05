<?php namespace Digbang\L4Backoffice\Auth\Controllers;

use Cartalyst\Sentry\Sentry;
use Cartalyst\Sentry\Users\LoginRequiredException;
use Cartalyst\Sentry\Users\PasswordRequiredException;
use Cartalyst\Sentry\Users\UserAlreadyActivatedException;
use Cartalyst\Sentry\Users\UserNotActivatedException;
use Cartalyst\Sentry\Users\UserNotFoundException;
use Cartalyst\Sentry\Users\WrongPasswordException;
use Digbang\Security\Auth\AccessControl;
use Digbang\Security\Auth\Emailer;
use Illuminate\Config\Repository;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\MessageBag;

class AuthController extends Controller
{
	/**
	 * @type string
	 */
	protected $layout = 'backoffice::layouts.default';

	/**
	 * @type Sentry
	 */
	protected $sentry;

	/**
	 * @type Redirector
	 */
	protected $redirector;

	/**
	 * @type AccessControl
	 */
	protected $accessControl;

	/**
	 * @type Emailer
	 */
	protected $emailer;

	/**
	 * @type Request
	 */
	private $request;
	/**
	 * @type Repository
	 */
	private $config;

	/**
	 * @param Redirector    $redirector
	 * @param AccessControl $accessControl
	 * @param Emailer       $emailer
	 * @param Sentry        $sentry
	 * @param Request       $request
	 *
	 * @param Repository    $config
	 *
	 * @internal param SessionManager $session
	 */
	public function __construct(Redirector $redirector, AccessControl $accessControl, Emailer $emailer, Sentry $sentry, Request $request, Repository $config)
	{
		$this->redirector    = $redirector;
		$this->accessControl = $accessControl;
		$this->emailer       = $emailer;
		$this->sentry        = $sentry;
		$this->request       = $request;
		$this->config        = $config;
	}

	/**
	 * @return \Illuminate\View\View
	 */
	public function login()
	{
		return View::make('backoffice::auth.login');
	}

	/**
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function authenticate()
	{
		$errors = new MessageBag();

		try
		{
			$this->accessControl->authenticate(
				$this->request->get('email'),
				$this->request->get('password'),
				$this->request->get('remember')
			);

			return $this->redirector->intended(route('backoffice.index'));
		}
		catch (LoginRequiredException $e)
		{
			$errors->add('email', trans('backoffice::auth.validation.login-required'));
		}
		catch (PasswordRequiredException $e)
		{
			$errors->add('password', trans('backoffice::auth.validation.password.required'));
		}
		catch (WrongPasswordException $e)
		{
			$errors->add('password', trans('backoffice::auth.validation.password.wrong'));
		}
		catch (UserNotFoundException $e)
		{
			$errors->add('email', trans('backoffice::auth.validation.user.not-found'));
		}
		catch (UserNotActivatedException $e)
		{
			$errors->add('email', trans('backoffice::auth.validation.user.not-activated'));
		}
		
		return $this->redirector->route('backoffice.auth.login')->withInput()->withErrors($errors);
	}

	/**
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function logout()
	{
		$this->accessControl->logout();

		return $this->redirector->route('backoffice.auth.login');
	}

	/**
	 * @param string $activationCode
	 *
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
	 */
	public function activate($activationCode)
	{
		try
		{
			if ($this->accessControl->activate($activationCode))
			{
				return $this->redirector->route('backoffice.auth.login')
					->with([
						'success' => trans('backoffice::auth.activation.success')
					]);
			}

			return View::make('backoffice::auth.activation-expired', [
				'email' => $this->config->get('backoffice::auth.contact')
			]);
		}
		catch (UserNotFoundException $e)
		{
			return $this->redirector->route('backoffice.auth.login')
				->with(['danger' => trans('backoffice::auth.validation.user.not-found')]);
		}
		catch (UserAlreadyActivatedException $e)
		{
			return $this->redirector->route('backoffice.auth.login')
				->with(['warning' => trans('backoffice::auth.validation.user.already-active')]);
		}
	}

	/**
	 * @param $id
	 * @param $resetCode
	 *
	 * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
	 */
	public function resetPassword($id, $resetCode)
	{
		if ($this->accessControl->checkResetPasswordCode($id, $resetCode))
		{
			return View::make('backoffice::auth.reset-password', [
				'id'        => $id,
				'resetCode' => $resetCode
			]);
		}

		return $this->redirector->route('backoffice.auth.login')->with('danger', trans('backoffice::auth.validation.reset-password.incorrect'));
	}

	/**
	 * @param $id
	 *
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function resetPasswordRequest($id)
	{
		$input = $this->request->only(['password', 'password_confirmation', 'id']);
		$input['url-id'] = $id;

		/** @type \Illuminate\Validation\Validator $validator */
		$validator = Validator::make($input, [
			'password' => 'required|confirmed',
			'id'       => 'same:url-id'
		]);

		if ($validator->fails())
		{
			return $this->redirector->back()->withErrors($validator->errors());
		}

		try
		{
			if ($this->accessControl->resetPassword($this->request->get('reset_password_code'), $input['password']))
			{
				return $this->redirector->route('backoffice.auth.login')
					->with('success', trans('backoffice::auth.reset-password.success'));
			}

			return $this->redirector->route('backoffice.auth.login')
				->with('danger', trans('backoffice::auth.validation.reset-password.incorrect'));
		}
		catch (UserNotFoundException $e)
		{
			return $this->redirector->route('backoffice.auth.login')
				->with('danger', trans('backoffice::auth.validation.user.not-found'));
		}
	}

	/**
	 * @return \Illuminate\View\View
	 */
	public function forgotPassword()
	{
		return View::make('backoffice::auth.request-reset-password');
	}

	/**
	 * @return \Illuminate\Http\RedirectResponse
	 */
	public function forgotPasswordRequest()
	{
		try
		{
			$email = trim($this->request->get('email'));
			if (!$email)
			{
				throw new UserNotFoundException(trans('backoffice::auth.validation.reset-password.email'));
			}

			/* @var $user \Digbang\Security\Contracts\User */
			$user = $this->sentry->findUserByLogin($this->request->get('email'));

			$this->emailer->sendPasswordReset(
				$user,
				route('backoffice.auth.password.reset', [$user->getId(), $user->getResetPasswordCode()])
			);

			return $this->redirector->route('backoffice.auth.login')
				->with('info', trans('backoffice::auth.reset-password.email-sent', ['email' => $user->getEmail()]));
		}
		catch (UserNotFoundException $e)
		{
			return $this->redirector->back()->withErrors(['email' => trans('backoffice::auth.validation.user.not-found')]);
		}
	}
}
