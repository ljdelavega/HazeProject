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
        session_start();

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

        return true;
    }

    function UpdateDBRecForConfirmation(&$user_rec)
    {
        if(!$this->DBLogin())
        {
            $this->HandleError("Database login failed!");
            return false;
        }
        $confirmcode = $this->SanitizeForSQL($_GET['code']);

        $result = mysqli_query("Select name, email from $this->tablename where confirmcode='$confirmcode'",$this->connection);
        if(!$result || mysqli_num_rows($result) <= 0)
        {
            $this->HandleError("Wrong confirm code.");
            return false;
        }
        $row = mysqli_fetch_assoc($result);
        $user_rec['name'] = $row['name'];
        $user_rec['email']= $row['email'];

        $qry = "Update $this->tablename Set confirmcode='y' Where  confirmcode='$confirmcode'";

        if(!mysqli_query( $qry ,$this->connection))
        {
            $this->HandleDBError("Error inserting data to the table\nquery:$qry");
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

//TODO: Fix this for our registration form
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

//TODO: Fix this for our registration form
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
        // Create connection
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

    function Ensuretable()
    {
        $result = mysqli_query($this->connection, "SHOW COLUMNS FROM $this->tablename");
        if(!$result || mysqli_num_rows($result) <= 0)
        {
            return $this->CreateTable();
        }
        return true;
    }

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
}
?>
