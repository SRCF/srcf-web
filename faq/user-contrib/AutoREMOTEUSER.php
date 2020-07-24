<?php
// vim:sw=2:softtabstop=2:textwidth=80
//
// This program is free software: you can redistribute it and/or modify it
// under the terms of the GNU General Public License as published by the Free
// Software Foundation, either version 3 of the License, or (at your option)
// any later version.
//
// This program is distributed in the hope that it will be useful, but WITHOUT
// ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
// FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for
// more details.
//
// You should have received a copy of the GNU General Public License along with
// this program.  If not, see <http://www.gnu.org/licenses/>.
//
// Copyright 2006 Otheus Shelling
// Copyright 2007 Rusty Burchfield
//
// See http://meta.wikimedia.org/wiki/User:Otheus/Auto_Login_via_REMOTE_USER
// Adapted by Rusty to be compatible with version 1.9 of MediaWiki
//
// Add these two lines to the bottom of your LocalSettings.php
// require_once('extensions/Auth_remoteuser.php');
// $wgAuth = new Auth_remoteuser();
//
// The constructor of Auth_remoteuser registers a hook to do the automatic
// login.  Storing the Auth_remoteuser object in $wgAuth tells mediawiki to use
// that object as the AuthPlugin.  This way the login attempts by the hook will
// be handled by us.
//
// You probably want to edit the initUser function to set the users real name
// and email address properly for your configuration.


// Modified by Nicholas Wilson © 2008-9 for use with Raven; GPL 3 or later


//Extension credits that show up on Special:Version
$wgExtensionCredits['other'][] = array(
        'name' => 'AutomaticREMOTE USER',
        'version' => '1.0',
        'author' => array('Otheus Shelling', 'Rusty Burchfield'),
        'url' => 'http://www.mediawiki.org/wiki/Extension:AutomaticREMOTE_USER',
        'description' => 'Automatically logs users using the REMOTE_USER environment variable.',
);


// The Auth_remoteuser class is an AuthPlugin so make sure we have this
// included.
require_once('AuthPlugin.php');

/**
 * This hook is registered by the Auth_remoteuser constructor.  It will be
 * called on every page load.  It serves the function of automatically logging
 * in the user.  The Auth_remoteuser class is an AuthPlugin and handles the
 * actual authentication, user creation, etc.
 *
 * Details:
 * 1. Check to see if the user has a session and is not anonymous.  If this is
 *    true we can just return.
 * 2. If the user doesn't have a session, we create a login form with our own
 *    fake request and ask the form to authenticate the user.  If the user does
 *    not exist authenticateUserData will attempt to create one.  The login form
 *    uses our Auth_remoteuser class as an AuthPlugin.
 *
 * Note: If cookies are disabled, an infinite loop /might/ occur?
 */
function Auth_remote_user_hook() {
  global $wgUser;
  global $wgRequest;
  global $_REQUEST;

  // For a few special pages, don't do anything.
  $title = $wgRequest->getVal('title');
  if (($title == Title::makeName(NS_SPECIAL, 'Userlogout')) ||
      ($title == Title::makeName(NS_SPECIAL, 'Userlogin'))) {
    return;
  }

  // Do nothing if session is valid
  $user = User::newFromSession();
  if (!$user->isAnon()) {
    return;  // User is already logged in and not anonymous.
  }

  // Copied from includes/SpecialUserlogin.php
  if(!isset($wgCommandLineMode) && !isset($_COOKIE[session_name()])) {
    wfSetupSession();
  }

  // Submit a fake login form to authenticate the user.
  $username = $_SERVER['REMOTE_USER'];
  $params = new FauxRequest(array(
        'wpName' => $username,
        'wpPassword' => '',
        'wpDomain' => '',
        'wpRemember' => ''
        ));

  // Authenticate user data will automatically create new users.
  $loginForm = new LoginForm($params);
  $result = $loginForm->authenticateUserData();
  if ($result != LoginForm::SUCCESS) {
    error_log('Unexpected REMOTE_USER authentication failure.');
    return;
  }

  $wgUser->setCookies();
  return;  // User has been logged in.
}

class Auth_remoteuser extends AuthPlugin {

  function Auth_remoteuser() {
    // Register our hook function.  This hook will be executed on every page
    // load.  Its purpose is to automatically log the user in, if necessary.
    if ( strlen($_SERVER['REMOTE_USER']) ) {
      global $wgExtensionFunctions;
      if (!isset($wgExtensionFunctions)) {
        $wgExtensionFunctions = array();
      }
      else if (!is_array($wgExtensionFunctions)) {
        $wgExtensionFunctions = array( $wgExtensionFunctions );
      }
      array_push($wgExtensionFunctions, 'Auth_remote_user_hook');
    }
    return;
  }

  /**
   * Disallow password change.
   *
   * @return bool
   */
  function allowPasswordChange() {
    return false;
  }

  /**
   * This should not be called because we do not allow password change.  Always
   * fail by returning false.
   *
   * @param $user User object.
   * @param $password String: password.
   * @return bool
   * @public
   */
  function setPassword($user, $password) {
    return false;
  }

  /**
   * We don't support this but we have to return true for preferences to save.
   *
   * @param $user User object.
   * @return bool
   * @public
   */
  function updateExternalDB($user) {
    return true;
  }

  /**
   * We can't create external accounts so return false.
   *
   * @return bool
   * @public
   */
  function canCreateAccounts() {
    return false;
  }

  /**
   * We don't support adding users to whatever service provides REMOTE_USER, so
   * fail by always returning false.
   *
   * @param User $user
   * @param string $password
   * @return bool
   * @public
   */
  function addUser($user, $password) {
    return false;
  }


  /**
   * Pretend all users exist.  This is checked by authenticateUserData to
   * determine if a user exists in our 'db'.  By returning true we tell it that
   * it can create a local wiki user automatically.
   *
   * @param $username String: username.
   * @return bool
   * @public
   */
  function userExists($username) {
    return true;
  }

  /**
   * Check whether the given name matches REMOTE_USER.
   * The name will be normalized to MediaWiki's requirements, so
   * lower it and the REMOTE_USER before checking.
   *
   * @param $username String: username.
   * @param $password String: user password.
   * @return bool
   * @public
   */
  function authenticate($username, $password) {
    global $_SERVER;
    return isset($_SERVER['REMOTE_USER']) &&
           (strtolower($username) == strtolower($_SERVER['REMOTE_USER']));
  }

  /**
   * Check to see if the specific domain is a valid domain.
   *
   * @param $domain String: authentication domain.
   * @return bool
   * @public
   */
  function validDomain($domain) {
    return true;
  }

  /**
   * When a user logs in, optionally fill in preferences and such.
   * For instance, you might pull the email address or real name from the
   * external user database.
   *
   * The User object is passed by reference so it can be modified; don't
   * forget the & on your function declaration.
   *
   * @param User $user
   * @public
   */
  function updateUser(&$user) {
    // We only set this stuff when accounts are created.
    return true;
  }

  /**
   * Return true because the wiki should create a new local account
   * automatically when asked to login a user who doesn't exist locally but
   * does in the external auth database.
   *
   * @return bool
   * @public
   */
  function autoCreate() {
    return true;
  }

  /**
   * Return true to prevent logins that don't authenticate here from being
   * checked against the local database's password fields.
   *
   * @return bool
   * @public
   */
  function strict() {
    return true;
  }

  /**
   * When creating a user account, optionally fill in preferences and such.
   * For instance, you might pull the email address or real name from the
   * external user database.
   *
   * @param $user User object.
   * @public
   */
  function initUser(&$user) {
    global $_SERVER;
    $username = $_SERVER['REMOTE_USER'];

    //LDAP from Hermes is an option here if needed
    $user->setRealName('');
    $user->setEmail($username . '@cam.ac.uk');

    $user->mEmailAuthenticated = wfTimestampNow();
    $user->setToken();

    //turn on e-mail notifications by default
    $user->setOption('enotifwatchlistpages', 1);
    $user->setOption('enotifusertalkpages', 1);
    $user->setOption('enotifminoredits', 1);
    $user->setOption('enotifrevealaddr', 1);

    $user->saveSettings();
  }

  /**
   * Modify options in the login template.  This shouldn't be very important
   * because no one should really be bothering with the login page.
   *
   * @param $template UserLoginTemplate object.
   * @public
   */
  function modifyUITemplate(&$template) {
    //disable the mail new password box
    $template->set('useemail', false);
    //disable 'remember me' box
    $template->set('remember', false);
    $template->set('create', false);
    $template->set('domain', false);
    $template->set('usedomain', false);
  }

  /**
   * Normalize user names to the MediaWiki standard to prevent duplicate
   * accounts.
   *
   * @param $username String: username.
   * @return string
   * @public
   */
  function getCanonicalName($username) {
    // lowercase the username
    $username = strtolower($username);
    // uppercase first letter to make MediaWiki happy
    $username[0] = strtoupper($username[0]);
    return $username;
  }
}