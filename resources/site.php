<?PHP
/*
    Registration/Login script from HTML Form Guide
    V1.0

    This program is free software published under the
    terms of the GNU Lesser General Public License.
    http://www.gnu.org/copyleft/lesser.html


This program is distributed in the hope that it will
be useful - WITHOUT ANY WARRANTY; without even the
implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.

For updates, please visit:
http://www.html-form-guide.com/php-form/php-registration-form.html
http://www.html-form-guide.com/php-form/php-login-form.html

*/
require_once("library/class.phpmailer.php");
require_once("library/formvalidator.php");

/*
This class will contain the main information for the website
and will be initialized in the config.php
Write various utility functions in this class.
*/
class Site
{
    var $from_address;

    var $username;
    var $pwd;
    var $database;
    var $tablename;
    var $connection;
    var $rand_key;

    var $error_message;

    //-----Initialization -------
    function Site()
    {
        $this->sitename = 'haze.com';
        $this->rand_key = '0iQx5oBk66oVZep';
    }

    function InitDB($host,$uname,$pwd,$database,$tablename)
    {
        $this->db_host  = $host;
        $this->username = $uname;
        $this->pwd  = $pwd;
        $this->database  = $database;
        $this->tablename = $tablename;
    }

    function SetWebsiteName($sitename)
    {
        $this->sitename = $sitename;
    }

    function SetRandomKey($key)
    {
        $this->rand_key = $key;
    }

    //-------Main Operations ----------------------
    function RegisterUser()
    {
        if(!isset($_POST['submitted']))
        {
           return false;
        }

        $formvars = array();

        if(!$this->ValidateRegistrationSubmission())
        {
            return false;
        }

        $this->CollectRegistrationSubmission($formvars);

        if(!$this->SaveToDatabase($formvars))
        {
            return false;
        }

        return true;
    }

    function RegisterGame()
    {
        if(!isset($_POST['submitted']))
        {
           return false;
        }

        $formvars = array();

        if(!$this->ValidateGameSubmission())
        {
            return false;
        }

        $this->CollectGameSubmission($formvars);

        if(!$this->SaveGameToDatabase($formvars))
        {
            return false;
        }

        return true;
    }

    function RegisterReview()
    {
        if(!isset($_POST['submitted']))
        {
           return false;
        }

        $formvars = array();

        if(!$this->ValidateReviewSubmission())
        {
            return false;
        }

        $this->CollectReviewSubmission($formvars);

        if(!$this->SaveReviewToDatabase($formvars))
        {
            return false;
        }

        return true;
    }


    function Login()
    {
        if(empty($_POST['username']))
        {
            $this->HandleError("UserName is empty!");
            return false;
        }

        if(empty($_POST['password']))
        {
            $this->HandleError("Password is empty!");
            return false;
        }

        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        if(!isset($_SESSION)){ session_start(); }
        if(!$this->CheckLoginInDB($username,$password))
        {
            return false;
        }

        $_SESSION[$this->GetLoginSessionVar()] = $username;

        return true;
    }

    function CheckLogin()
    {
         if(!isset($_SESSION)){ session_start(); }

         $sessionvar = $this->GetLoginSessionVar();

         if(empty($_SESSION[$sessionvar]))
         {
            return false;
         }
         return true;
    }

    function UserFullName()
    {
        return isset($_SESSION['name_of_user'])?$_SESSION['name_of_user']:'';
    }

    function UserEmail()
    {
        return isset($_SESSION['email_of_user'])?$_SESSION['email_of_user']:'';
    }

    function LogOut()
    {

        $sessionvar = $this->GetLoginSessionVar();

        $_SESSION[$sessionvar]=NULL;

        unset($_SESSION[$sessionvar]);
    }

    function EmailResetPasswordLink()
    {
        if(empty($_POST['email']))
        {
            $this->HandleError("Email is empty!");
            return false;
        }
        $user_rec = array();
        if(false === $this->GetUserFromEmail($_POST['email'], $user_rec))
        {
            return false;
        }
        if(false === $this->SendResetPasswordLink($user_rec))
        {
            return false;
        }
        return true;
    }

    function ResetPassword()
    {
        if(empty($_GET['email']))
        {
            $this->HandleError("Email is empty!");
            return false;
        }
        if(empty($_GET['code']))
        {
            $this->HandleError("reset code is empty!");
            return false;
        }
        $email = trim($_GET['email']);
        $code = trim($_GET['code']);

        if($this->GetResetPasswordCode($email) != $code)
        {
            $this->HandleError("Bad reset code!");
            return false;
        }

        $user_rec = array();
        if(!$this->GetUserFromEmail($email,$user_rec))
        {
            return false;
        }

        $new_password = $this->ResetUserPasswordInDB($user_rec);
        if(false === $new_password || empty($new_password))
        {
            $this->HandleError("Error updating new password");
            return false;
        }

        if(false == $this->SendNewPassword($user_rec,$new_password))
        {
            $this->HandleError("Error sending new password");
            return false;
        }
        return true;
    }

    function ChangePassword()
    {
        if(!$this->CheckLogin())
        {
            $this->HandleError("Not logged in!");
            return false;
        }

        if(empty($_POST['oldpwd']))
        {
            $this->HandleError("Old password is empty!");
            return false;
        }
        if(empty($_POST['newpwd']))
        {
            $this->HandleError("New password is empty!");
            return false;
        }

        $user_rec = array();
        if(!$this->GetUserFromEmail($this->UserEmail(),$user_rec))
        {
            return false;
        }

        $pwd = trim($_POST['oldpwd']);

        if($user_rec['password'] != md5($pwd))
        {
            $this->HandleError("The old password does not match!");
            return false;
        }
        $newpwd = trim($_POST['newpwd']);

        if(!$this->ChangePasswordInDB($user_rec, $newpwd))
        {
            return false;
        }
        return true;
    }

    //-------Public Helper functions -------------
    function GetSelfScript()
    {
        return htmlentities($_SERVER['PHP_SELF']);
    }

    function SafeDisplay($value_name)
    {
        if(empty($_POST[$value_name]))
        {
            return'';
        }
        return htmlentities($_POST[$value_name]);
    }

    function RedirectToURL($url)
    {
        header("Location: $url");
        exit;
    }

    function GetSpamTrapInputName()
    {
        return 'sp'.md5('KHGdnbvsgst'.$this->rand_key);
    }

    function GetErrorMessage()
    {
        if(empty($this->error_message))
        {
            return '';
        }
        $errormsg = nl2br(htmlentities($this->error_message));
        return $errormsg;
    }
    //-------Private Helper functions-----------

    function HandleError($err)
    {
        $this->error_message .= $err."\r\n";
    }

    function HandleDBError($err)
    {
        $this->HandleError($err."\r\n mysqlerror:". mysqli_error($this->connection));
    }

    function GetFromAddress()
    {
        if(!empty($this->from_address))
        {
            return $this->from_address;
        }

        $host = $_SERVER['SERVER_NAME'];

        $from ="nobody@$host";
        return $from;
    }

    function GetLoginSessionVar()
    {
        $retvar = md5($this->rand_key);
        $retvar = 'usr_'.substr($retvar,0,10);
        return $retvar;
    }

    function CheckLoginInDB($username,$password)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }
        $username = $this->SanitizeForSQL($username);
        $pwd = $this->SanitizeForSQL($password);
        $qry = "SELECT * FROM $this->tablename WHERE username='$username' AND password='$pwd'";

        $result = mysqli_query($this->connection, $qry);

        if(!$result)
        {
          $this->HandleError("Error logging in. Query was: " . $qry);
          return false;
        }

        if(mysqli_num_rows($result) <= 0)
        {
            $this->HandleError("Error logging in. The username or password does not match.");
            return false;
        }

        $row = mysqli_fetch_assoc($result);

        // Save user info into session on successful login.
        $_SESSION['username']  = $row['username'];
        $_SESSION['email'] = $row['email'];
        $_SESSION['list_id'] = $row['list_id'];
        $_SESSION['firstname'] = $row['firstname'];
        $_SESSION['lastname'] = $row['lastname'];
        $_SESSION['password'] = $row['password'];

        return true;
    }

    function UpdateUser()
    {
        if(!isset($_POST['submitted']))
        {
           return false;
        }

        $formvars = array();

        $this->CollectUpdateSubmission($formvars);

        if(!$this->VerifyUpdate($formvars))
        {
            return false;
        }

        return true;
    }

    function CollectUpdateSubmission(&$formvars)
    {
        $formvars['first_name'] = $this->Sanitize($_POST['first_name']);
        $formvars['last_name'] = $this->Sanitize($_POST['last_name']);
        $formvars['input_email'] = $this->Sanitize($_POST['input_email']);
        $formvars['password'] = $this->Sanitize($_POST['password']);
        $formvars['password_reconfirm'] = $this->Sanitize($_POST['password_reconfirm']);
    }

    function VerifyUpdate(&$formvars)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }
        if(!$this->Ensuretable())
        {
            return false;
        }
        if(!$this->IsFieldUnique($formvars,'input_email'))
        {
            $this->HandleError("This email is already registered");
            return false;
        }

        if(!$this->UpdateDB($formvars))
        {
            $this->HandleError("Updating the Database failed!");
            return false;
        }
        return true;
    }

    function UpdateDB(&$formvars)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }
        // Get variables from form
        $password = $this->SanitizeForSQL($formvars["password"]);
        $firstname = $this->SanitizeForSQL($formvars["first_name"]);
        $lastname = $this->SanitizeForSQL($formvars["last_name"]);
        $email = $this->SanitizeForSQL($formvars["input_email"]);
        $list_id = $_SESSION['list_id'];

        $update_query = "UPDATE User SET password='$password', firstname='$firstname', lastname='$lastname', email='$email' WHERE list_id='$list_id'";

        if(!mysqli_query($this->connection, $update_query))
        {
            $this->HandleDBError("Error updating data in the User table\nquery:$update_query");
            return false;
        }
        //update session vars after successful update.
        $_SESSION['email'] = $email;
        $_SESSION['list_id'] = $list_id;
        $_SESSION['firstname'] = $firstname;
        $_SESSION['lastname'] = $lastname;
        $_SESSION['password'] = $password;

        return true;
    }

    function DeleteUser()
    {
      if(!$this->DBLogin())
      {
          $this->HandleError("Database login failed!");
          return false;
      }

      $list_id = $_SESSION['list_id'];
      $username = $_SESSION['username'];
      // sql to delete a User record
      $delete_query = "DELETE FROM User WHERE username = '$username' AND list_id='$list_id'";

      if (mysqli_query($this->connection, $delete_query)) {
          echo "User: " . $username . " deleted successfully";
      } else {
          $this->HandleDBError("Error deleting user from the User table\nquery:$delete_query");
          return false;
      }

      return true;
    }

    function ResetUserPasswordInDB($user_rec)
    {
        $new_password = substr(md5(uniqid()),0,10);

        if(false == $this->ChangePasswordInDB($user_rec,$new_password))
        {
            return false;
        }
        return $new_password;
    }

    function ChangePasswordInDB($user_rec, $newpwd)
    {
        $newpwd = $this->SanitizeForSQL($newpwd);

        $qry = "Update $this->tablename Set password='".md5($newpwd)."' Where  id_user=".$user_rec['id_user']."";

        if(!mysqli_query( $qry ,$this->connection))
        {
            $this->HandleDBError("Error updating the password \nquery:$qry");
            return false;
        }
        return true;
    }

    function GetUserFromEmail($email,&$user_rec)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }
        $email = $this->SanitizeForSQL($email);

        $result = mysqli_query("Select * from $this->tablename where email='$email'",$this->connection);

        if(!$result || mysqli_num_rows($result) <= 0)
        {
            $this->HandleError("There is no user with email: $email");
            return false;
        }
        $user_rec = mysqli_fetch_assoc($result);


        return true;
    }


    function GetResetPasswordCode($email)
    {
       return substr(md5($email.$this->sitename.$this->rand_key),0,10);
    }

    function SendResetPasswordLink($user_rec)
    {
        $email = $user_rec['email'];

        $mailer = new PHPMailer();

        $mailer->CharSet = 'utf-8';

        $mailer->AddAddress($email,$user_rec['name']);

        $mailer->Subject = "Your reset password request at ".$this->sitename;

        $mailer->From = $this->GetFromAddress();

        $link = $this->GetAbsoluteURLFolder().
                '/resetpwd.php?email='.
                urlencode($email).'&code='.
                urlencode($this->GetResetPasswordCode($email));

        $mailer->Body ="Hello ".$user_rec['name']."\r\n\r\n".
        "There was a request to reset your password at ".$this->sitename."\r\n".
        "Please click the link below to complete the request: \r\n".$link."\r\n".
        "Regards,\r\n".
        "Webmaster\r\n".
        $this->sitename;

        if(!$mailer->Send())
        {
            return false;
        }
        return true;
    }

    function SendNewPassword($user_rec, $new_password)
    {
        $email = $user_rec['email'];

        $mailer = new PHPMailer();

        $mailer->CharSet = 'utf-8';

        $mailer->AddAddress($email,$user_rec['name']);

        $mailer->Subject = "Your new password for ".$this->sitename;

        $mailer->From = $this->GetFromAddress();

        $mailer->Body ="Hello ".$user_rec['name']."\r\n\r\n".
        "Your password is reset successfully. ".
        "Here is your updated login:\r\n".
        "username:".$user_rec['username']."\r\n".
        "password:$new_password\r\n".
        "\r\n".
        "Login here: ".$this->GetAbsoluteURLFolder()."/login.php\r\n".
        "\r\n".
        "Regards,\r\n".
        "Webmaster\r\n".
        $this->sitename;

        if(!$mailer->Send())
        {
            return false;
        }
        return true;
    }

    function ValidateRegistrationSubmission()
    {
        //This is a hidden input field. Humans won't fill this field.
        if(!empty($_POST[$this->GetSpamTrapInputName()]) )
        {
            //The proper error is not given intentionally
            $this->HandleError("Automated submission prevention: case 2 failed");
            return false;
        }

        $validator = new FormValidator();
        $validator->addValidation("username","req","Please provide your username!");
        $validator->addValidation("first_name","req","Please provide your first name!");
        $validator->addValidation("last_name","req","Please provide your last name!");
        $validator->addValidation("input_email","req","Please provide your email address!");
        $validator->addValidation("input_email","email","Please provide a valid email address!");
        $validator->addValidation("password","req","Please provide a password!");
        $validator->addValidation("password_reconfirm","req","Please confirm your password!");

        if(!$validator->ValidateForm())
        {
            $error='';
            $error_hash = $validator->GetErrors();
            foreach($error_hash as $inpname => $inp_err)
            {
                $error .= $inpname.':'.$inp_err."\n";
            }
            $this->HandleError($error);
            return false;
        }
        return true;
    }

    function CollectRegistrationSubmission(&$formvars)
    {
        $formvars['username'] = $this->Sanitize($_POST['username']);
        $formvars['first_name'] = $this->Sanitize($_POST['first_name']);
        $formvars['last_name'] = $this->Sanitize($_POST['last_name']);
        $formvars['input_email'] = $this->Sanitize($_POST['input_email']);
        $formvars['password'] = $this->Sanitize($_POST['password']);
        $formvars['password_reconfirm'] = $this->Sanitize($_POST['password_reconfirm']);
    }

    function SaveToDatabase(&$formvars)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }
        if(!$this->Ensuretable())
        {
            return false;
        }
        if(!$this->IsFieldUnique($formvars,'input_email'))
        {
            $this->HandleError("This email is already registered");
            return false;
        }

        if(!$this->IsFieldUnique($formvars,'username'))
        {
            $this->HandleError("This Username is already in use. Please try another username.");
            return false;
        }
        if(!$this->InsertIntoDB($formvars))
        {
            $this->HandleError("Inserting to Database failed!");
            return false;
        }
        return true;
    }

function UpdateGame()
    {
        if(!isset($_POST['submitted']))
        {
           return false;
        }

        $formvars = array();

        $this->CollectGameUpdateSubmission($formvars);

        if(!$this->VerifyGameUpdate($formvars))
        {
            return false;
        }

        return true;
    }

    function CollectGameUpdateSubmission(&$formvars)
    {
      $formvars['game_name'] = $this->Sanitize($_POST['game_name']);
      $formvars['genre'] = $this->Sanitize($_POST['genre']);
      $formvars['price'] = $this->Sanitize($_POST['price']);
      $formvars['hours'] = $this->Sanitize($_POST['hours']);
      $formvars['completion_state'] = $this->Sanitize($_POST['completion_state']);
    }

    function VerifyGameUpdate(&$formvars)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }
        if(!$this->EnsureGametable())
        {
            return false;
        }

        if(!$this->UpdateGameDB($formvars))
        {
            $this->HandleError("Updating the Database failed!");
            return false;
        }
        return true;
    }

    function UpdateGameDB(&$formvars)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }
        // Get variables from form for Game table update
        $game_id = $_SESSION['game_id'];
        $gamename = $this->SanitizeForSQL($formvars["game_name"]);
        $genre = $this->SanitizeForSQL($formvars["genre"]);
        $price = $this->SanitizeForSQL($formvars["price"]);
        $game_query = "UPDATE Game SET game_name='$gamename', genre='$genre', price='$price' WHERE game_id = '$game_id'";

        if(!mysqli_query($this->connection, $game_query))
        {
            $this->HandleDBError("Error updating data in the Game table\nquery:$game_query");
            return false;
        }

        // Now update the completion state
        // Get variables from form for CompletionState table update
        $completion_state = $this->SanitizeForSQL($formvars["completion_state"]);
        $hours = $this->SanitizeForSQL($formvars["hours"]);

        $completion_state_query = "UPDATE CompletionState SET state='$completion_state', hours='$hours' WHERE game_id = '$game_id'";

        if(!mysqli_query($this->connection, $completion_state_query))
        {
            $this->HandleDBError("Error updating data in the CompletionState table\nquery:$completion_state_query");
            return false;
        }
        return true;
    }

    function DeleteGame()
    {
      if(!$this->DBLogin())
      {
          $this->HandleError("Database login failed!");
          return false;
      }

      // sql to delete a Game record
	    $game_id = $_SESSION['game_id'];
      $delete_query = "DELETE FROM Game WHERE game_id = '$game_id'";

      if (mysqli_query($this->connection, $delete_query)) {
          echo "Game deleted successfully";
      } else {
          $this->HandleDBError("Error deleting title from the Game table\nquery:$delete_query");
          return false;
      }

      return true;
    }


    function ValidateGameSubmission()
    {
        //This is a hidden input field. Humans won't fill this field.
        if(!empty($_POST[$this->GetSpamTrapInputName()]) )
        {
            //The proper error is not given intentionally
            $this->HandleError("Automated submission prevention: case 2 failed");
            return false;
        }

        $validator = new FormValidator();
        $validator->addValidation("game_name","req","Please enter the title!");
        $validator->addValidation("genre","req","Please enter the genre!");
        $validator->addValidation("price","req","Please enter the price!");

        if(!$validator->ValidateForm())
        {
            $error='';
            $error_hash = $validator->GetErrors();
            foreach($error_hash as $inpname => $inp_err)
            {
                $error .= $inpname.':'.$inp_err."\n";
            }
            $this->HandleError($error);
            return false;
        }
        return true;
    }

    function CollectGameSubmission(&$formvars)
    {
        $formvars['game_name'] = $this->Sanitize($_POST['game_name']);
        $formvars['genre'] = $this->Sanitize($_POST['genre']);
        $formvars['price'] = $this->Sanitize($_POST['price']);
        $formvars['hours'] = $this->Sanitize($_POST['hours']);
        $formvars['completion_state'] = $this->Sanitize($_POST['completion_state']);
    }

    function SaveGameToDatabase(&$formvars)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }
        if(!$this->EnsureGametable())
        {
            return false;
        }

        if(!$this->IsFieldUnique($formvars,'game_name'))
        {
            $this->HandleError("This title already exists");
            return false;
        }
        if(!$this->InsertGameIntoDB($formvars))
        {
            $this->HandleError("Inserting to Database failed!");
            return false;
        }
        return true;
    }

    function UpdateReview()
    {
        if(!isset($_POST['submitted']))
        {
           return false;
        }

        $formvars = array();

        $this->CollectReviewSubmission($formvars);

        if(!$this->VerifyReviewUpdate($formvars))
        {
            return false;
        }

        return true;
    }

    function CollectReviewUpdateSubmission(&$formvars)
    {
        $formvars['username'] = $this->Sanitize($_POST['username']);
        $formvars['game_id'] = $this->Sanitize($_POST['game_id']);
        $formvars['rating'] = $this->Sanitize($_POST['rating']);
        $formvars['text_review'] = $this->Sanitize($_POST['text_review']);
    }

    function VerifyReviewUpdate(&$formvars)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }
        if(!$this->EnsureReviewtable())
        {
            return false;
        }

        if(!$this->UpdateReviewDB($formvars))
        {
            $this->HandleError("Updating the Database failed!");
            return false;
        }
        return true;
    }

    function UpdateReviewDB(&$formvars)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }
        // Get variables from form
        $username = $this->SanitizeForSQL($formvars["username"]);
        $game_id = $this->SanitizeForSQL($formvars["game_id"]);
        $rating = $this->SanitizeForSQL($formvars["rating"]);
        $text_review = $this->SanitizeForSQL($formvars["text_review"]);
        $update_query = "UPDATE Reviews SET rating='$rating', text_review='$text_review' WHERE game_id = '$game_id' AND username = '$username'";

        if(!mysqli_query($this->connection, $update_query))
        {
            $this->HandleDBError("Error updating data in the Reviews table\nquery:$update_query");
            return false;
        }
        return true;
    }

    function DeleteReview()
    {
      if(!$this->DBLogin())
      {
          $this->HandleError("Database login failed!");
          return false;
      }
    $username = $_SESSION['username'];
    $game_id = $_SESSION['game_id'];
      // sql to delete a Delete record
      $delete_query = "DELETE FROM Reviews WHERE username = '$username' and game_id = '$game_id'";

      if (mysqli_query($this->connection, $delete_query)) {
          echo "Review by: " . $username . " deleted successfully";
      } else {
          $this->HandleDBError("Error deleting review from the Reviews table\nquery:$delete_query");
          return false;
      }

      return true;
    }


    function ValidateReviewSubmission()
    {
        //This is a hidden input field. Humans won't fill this field.
        if(!empty($_POST[$this->GetSpamTrapInputName()]) )
        {
            //The proper error is not given intentionally
            $this->HandleError("Automated submission prevention: case 2 failed");
            return false;
        }

        $validator = new FormValidator();
        $validator->addValidation("username","req","Please enter the username!");
        $validator->addValidation("game_id","req","Please enter the game id!");
        $validator->addValidation("rating","req","Please enter the rating!");
        $validator->addValidation("text_review","req","Please enter the review text!");

        if(!$validator->ValidateForm())
        {
            $error='';
            $error_hash = $validator->GetErrors();
            foreach($error_hash as $inpname => $inp_err)
            {
                $error .= $inpname.':'.$inp_err."\n";
            }
            $this->HandleError($error);
            return false;
        }
        return true;
    }

    function CollectReviewSubmission(&$formvars)
    {
        $formvars['username'] = $this->Sanitize($_POST['username']);
        $formvars['game_id'] = $this->Sanitize($_POST['game_id']);
        $formvars['rating'] = $this->Sanitize($_POST['rating']);
        $formvars['text_review'] = $this->Sanitize($_POST['text_review']);
    }

    function SaveReviewToDatabase(&$formvars)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }
        if(!$this->EnsureReviewtable())
        {
            return false;
        }

        if(!$this->IsFieldUnique($formvars,'username') && !$this->IsFieldUnique($formvars,'game_id'))
        {
            $this->HandleError("A review for this game by this user already exists.");
            return false;
        }
        if(!$this->InsertReviewIntoDB($formvars))
        {
            $this->HandleError("Inserting to Database failed!");
            return false;
        }
        return true;
    }

    function InsertReviewIntoDB($formvars)
    {
      //insert into Game table
      $game_query = 'INSERT INTO Reviews (
              username,
              game_id,
              rating,
              text_review
              )
              values
              (
              "' . $this->SanitizeForSQL($formvars['username']) . '",
              "' . $this->SanitizeForSQL($formvars['game_id']) . '",
              "' . $this->SanitizeForSQL($formvars['rating']) . '",
              "' . $this->SanitizeForSQL($formvars['text_review']) . '"
              )';
      if(!($this->connection->query($game_query) === TRUE))
      {
          $this->HandleDBError("Error inserting data to the table\nquery:$game_query");
          return false;
      }
      return true;
    }



    function IsFieldUnique($formvars,$fieldname)
    {
        $field_val = $this->SanitizeForSQL($formvars[$fieldname]);
        $qry = "select username from $this->tablename where $fieldname='".$field_val."'";
        $result = ($this->connection->query($qry));
        if($result && mysqli_num_rows($result) > 0)
        {
            return false;
        }
        return true;
    }

    function DBLogin()
    {
        // Connect to MySQL
        $conn = new mysqli($this->db_host,$this->username,$this->pwd);
        // try to connect to haze_db, if you can't, then create it.
        if (!mysqli_select_db($conn, $this->database)) {
          // try to create the db if connection fails on first attempt.
          $this->CreateDB();
        }

        // Create connection to haze database
        $this->connection = new mysqli($this->db_host,$this->username,$this->pwd,$this->database);

        if(!$this->connection)
        {
            $this->HandleDBError("Database Login failed! Please make sure that the DB login credentials provided are correct");
            return false;
        }
        if(!mysqli_select_db($this->connection, $this->database))
        {
            $this->HandleDBError('Failed to select database: '.$this->database.' Please make sure that the database name provided is correct');
            return false;
        }
        return true;
    }

    // create the database if not already created
    function CreateDB()
    {
      // Connect to MySQL
      $conn = new mysqli($this->db_host,$this->username,$this->pwd);
      if ($conn->connect_error) {
          die('Could not connect: ' . $conn->connect_error);
      }

      $sql = 'CREATE DATABASE IF NOT EXISTS haze_db';
      // create the haze_db only if it doesn't already exist.
      if ($conn->query($sql) === TRUE) {
          echo "Database haze_db created successfully\n";
      } else {
          echo 'Error creating database: ' . $conn->error . "\n";
      }

      // Now create the tables.
      // Create connection to haze database
      $this->connection = new mysqli($this->db_host,$this->username,$this->pwd,$this->database);

      // user table
      $this->CreateTable();
      // Game, Contains, and CompletionState tables
      $this->CreateGameTable();
      // reviews table
      $this->CreateReviewsTable();

      $conn->close();
    }

    function Ensuretable()
    {
        $result = mysqli_query($this->connection, "SHOW COLUMNS FROM $this->tablename");
        if(!$result || mysqli_num_rows($result) <= 0)
        {
            return $this->CreateTable();
        }
        return true;
    }

    function EnsureGametable()
    {
        $result = mysqli_query($this->connection, "SHOW COLUMNS FROM Game");
        if(!$result || mysqli_num_rows($result) <= 0)
        {
            return $this->CreateGameTable();
        }
        return true;
    }

    function EnsureReviewtable()
    {
        $result = mysqli_query($this->connection, "SHOW COLUMNS FROM Reviews");
        if(!$result || mysqli_num_rows($result) <= 0)
        {
            return $this->CreateReviewsTable();
        }
        return true;
    }

    // this function creates the user table
    function CreateTable()
    {
        // sql to create User table if it doesn't already exist
        $qry = "CREATE TABLE IF NOT EXISTS User (
        username VARCHAR(30) NOT NULL UNIQUE,
        list_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        password VARCHAR(20) NOT NULL,
        firstname VARCHAR(30) NOT NULL,
        lastname VARCHAR(30) NOT NULL,
        email VARCHAR(50)
        )";

        if (!($this->connection->query($qry) === TRUE))
        {
            $this->HandleDBError("Error creating the table \nquery was\n $qry");
            return false;
        }
        return true;
    }

    function CreateGameTable()
    {
      // sql to create User table if it doesn't already exist
      $create_game = "CREATE TABLE IF NOT EXISTS Game (
  		game_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  		game_name VARCHAR(50) NOT NULL,
  		price DECIMAL(10,2) NOT NULL,
  		genre VARCHAR(30) NOT NULL
  		)";

      if (!($this->connection->query($create_game) === TRUE))
      {
          $this->HandleDBError("Error creating the table \nquery was\n $create_game");
          return false;
      }

      // sql to create CompletionState table if it doesn't already exist
      $create_completionstate = "CREATE TABLE IF NOT EXISTS CompletionState (
      list_id INT(6) UNSIGNED,
      game_id INT(6) UNSIGNED,
      hours INT(8) UNSIGNED,
      percent_complete DECIMAL(2,1),
      date_completed DATETIME,
      state VARCHAR(30),
      PRIMARY KEY (list_id, game_id),
      FOREIGN KEY (list_id) REFERENCES User(list_id) ON DELETE CASCADE ON UPDATE CASCADE,
      FOREIGN KEY (game_id) REFERENCES Game(game_id) ON DELETE CASCADE ON UPDATE CASCADE
      )";

      if ($this->connection->query($create_completionstate) === TRUE) {
          echo "Table CompletionState created successfully";
          echo "\r\n";
      } else {
          $this->HandleDBError("Error creating the table \nquery was\n $create_completionstate");
          return false;
      }

      // sql to create Contains table if it doesn't already exist
      $create_contains = "CREATE TABLE IF NOT EXISTS Contains (
      list_id INT(6) UNSIGNED,
      game_id INT(6) UNSIGNED,
      PRIMARY KEY (list_id, game_id),
      FOREIGN KEY (list_id) REFERENCES User(list_id) ON DELETE CASCADE ON UPDATE CASCADE,
      FOREIGN KEY (game_id) REFERENCES Game(game_id) ON DELETE CASCADE ON UPDATE CASCADE
      )";

      if ($this->connection->query($create_contains) === TRUE) {
          echo "Table Contains created successfully";
          echo "\r\n";
      } else {
        $this->HandleDBError("Error creating the table \nquery was\n $create_contains");
        return false;
      }

      return true;
    }

    function CreateReviewsTable()
    {
      // sql to create Reviews table if it doesn't already exist
      $sql = "CREATE TABLE IF NOT EXISTS Reviews (
      username VARCHAR(30) NOT NULL,
      game_id INT(6) UNSIGNED NOT NULL,
      rating INT(1) UNSIGNED,
      text_review TEXT,
      PRIMARY KEY (username, game_id),
      FOREIGN KEY (username) REFERENCES User(username) ON DELETE CASCADE ON UPDATE CASCADE,
      FOREIGN KEY (game_id) REFERENCES Game(game_id) ON DELETE CASCADE ON UPDATE CASCADE
      )";

      if ($this->connection->query($sql) === TRUE) {
          echo "Table Reviews created successfully";
          echo "\r\n";
      } else {
        $this->HandleDBError("Error creating the table \nquery was\n $sql");
          echo "\r\n";
      }
    }

    function InsertIntoDB(&$formvars)
    {
        $insert_query = 'INSERT INTO '.$this->tablename.'(
                username,
                password,
                firstname,
                lastname,
                email
                )
                values
                (
                "' . $this->SanitizeForSQL($formvars['username']) . '",
                "' . $this->SanitizeForSQL($formvars['password']) . '",
                "' . $this->SanitizeForSQL($formvars['first_name']) . '",
                "' . $this->SanitizeForSQL($formvars['last_name']) . '",
                "' . $this->SanitizeForSQL($formvars['input_email']) . '"
                )';
        if(!($this->connection->query($insert_query) === TRUE))
        {
            $this->HandleDBError("Error inserting data to the table\nquery:$insert_query");
            return false;
        }
        return true;
    }

    function InsertGameIntoDB(&$formvars)
    {
        //insert into Game table
        $game_query = 'INSERT INTO Game (
                game_name,
                genre,
                price
                )
                values
                (
                "' . $this->SanitizeForSQL($formvars['game_name']) . '",
                "' . $this->SanitizeForSQL($formvars['genre']) . '",
                "' . $this->SanitizeForSQL($formvars['price']) . '"
                )';
        if(!($this->connection->query($game_query) === TRUE))
        {
            $this->HandleDBError("Error inserting data to the table\nquery:$game_query");
            return false;
        }

        //get the id of the last generated query, in this case: game_id
        $game_id = mysqli_insert_id ( $this->connection );

        // now insert and link to CompletionState table
        //insert into Game table
        $state_query = 'INSERT INTO CompletionState (
                game_id,
                list_id,
                hours,
                state
                )
                values
                (
                "' . $game_id . '",
                "' . $_SESSION['list_id'] . '",
                "' . $this->SanitizeForSQL($formvars['hours']) . '",
                "' . $this->SanitizeForSQL($formvars['completion_state']) . '"
                )';
        if(!($this->connection->query($state_query) === TRUE))
        {
            $this->HandleDBError("Error inserting data to the table\nquery:$state_query");
            return false;
        }

        //Finally insert and link to Contains relationship table.
        $contains_query = 'INSERT INTO Contains (
                game_id,
                list_id
                )
                values
                (
                "' . $game_id . '",
                "' . $_SESSION['list_id'] . '"
                )';
        if(!($this->connection->query($contains_query) === TRUE))
        {
            $this->HandleDBError("Error inserting data to the table\nquery:$contains_query");
            return false;
        }
        return true;
    }

    function SanitizeForSQL($str)
    {
        if( function_exists( "mysqli_real_escape_string" ) )
        {
              $ret_str = mysqli_real_escape_string($this->connection, $str );
        }
        else
        {
              $ret_str = addslashes( $str );
        }
        return $ret_str;
    }

 /*
    Sanitize() function removes any potential threat from the
    data submitted. Prevents email injections or any other hacker attempts.
    if $remove_nl is true, newline chracters are removed from the input.
    */
    function Sanitize($str,$remove_nl=true)
    {
        $str = $this->StripSlashes($str);

        if($remove_nl)
        {
            $injections = array('/(\n+)/i',
                '/(\r+)/i',
                '/(\t+)/i',
                '/(%0A+)/i',
                '/(%0D+)/i',
                '/(%08+)/i',
                '/(%09+)/i'
                );
            $str = preg_replace($injections,'',$str);
        }

        return $str;
    }
    function StripSlashes($str)
    {
        if(get_magic_quotes_gpc())
        {
            $str = stripslashes($str);
        }
        return $str;
    }

    function GetGamesFromList($list)
    {
      if(!$this->DBLogin())
      {
          $this->HandleError("Database login failed!");
          return false;
      }

      // get rows from the Game relation and join with Contains to get the user's games.
      $qry = "SELECT * FROM Game
              LEFT JOIN Contains ON Game.game_id = Contains.game_id
              WHERE list_id='$list'
      ";
      $gamelist = mysqli_query($this->connection, $qry);
      if(!$gamelist)
      {
        $this->HandleError("Error getting Game relation. Query was: " . $qry);
        return false;
      }

      if(mysqli_num_rows($gamelist) <= 0)
      {
          $this->HandleError("There are no games in the list. Please add some games.");
          return false;
      }

      return $gamelist;

    }

    function GetGameByID($game_id)
    {
      if(!$this->DBLogin())
      {
          $this->HandleError("Database login failed!");
          return false;
      }

      // get rows from the Game relation and join with Contains to get the user's games.
      $qry = "SELECT * FROM Game
              WHERE game_id='$game_id'
      ";
      $game = mysqli_query($this->connection, $qry);
      if(!$game)
      {
        $this->HandleError("Error getting Game relation. Query was: " . $qry);
        return false;
      }

      if(mysqli_num_rows($game) <= 0)
      {
          $this->HandleError("There is no game with this ID: " . $game_id );
          return false;
      }

      return $game;
    }

    function GetCompletionStateByID($game_id)
    {
      if(!$this->DBLogin())
      {
          $this->HandleError("Database login failed!");
          return false;
      }

      // get rows from the Game relation and join with Contains to get the user's games.
      $qry = "SELECT * FROM CompletionState
              WHERE game_id='$game_id'
      ";
      $completion_state = mysqli_query($this->connection, $qry);
      if(!$completion_state)
      {
        $this->HandleError("Error getting ComplationState relation. Query was: " . $qry);
        return false;
      }

      if(mysqli_num_rows($completion_state) <= 0)
      {
          $this->HandleError("There is no Completion State with this ID: " . $game_id );
          return false;
      }

      return $completion_state;
    }

    function GetAllReviews()
    {
      if(!$this->DBLogin())
      {
          $this->HandleError("Database login failed!");
          return false;
      }

      // get rows from the Game relation and join with Contains to get the user's games.
      $qry =  "SELECT * FROM Reviews
                INNER JOIN Game
                ON Game.game_id = Reviews.game_id
              ";
      $reviewslist = mysqli_query($this->connection, $qry);
      if(!$reviewslist)
      {
        $this->HandleError("Error getting Reviews relation. Query was: " . $qry);
        return false;
      }

      if(mysqli_num_rows($reviewslist) <= 0)
      {
          $this->HandleError("There are no reviews found.");
          return false;
      }

      return $reviewslist;

    }
}
?>
