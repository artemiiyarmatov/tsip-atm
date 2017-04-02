<?php

namespace core;

/**
 * Class Identity.
 *
 * Essential for keep identification data of user 
 * such as username, password and other stuff.
 * This essential also includes methods for:
 * - check password in DB for duplication,
 * - compare password with password of user in DB,
 * - and so on.
 */
class Identity
{
    /**
     * Email of user.
     *
     * @var string $email email of user.
     */
    public $email;

    /**
     * Password of user.
     *
     * @var string $password password of user.
     */
    public $password;

    /**
     * Name of user.
     *
     * @var string $name Name of user.
     */
    public $name;

    /**
     * Surname of user.
     *
     * @var string $surname surname of user.
     */
    public $surname;

    /**
     * Patronymic of user.
     *
     * @var string $patronymic patronymic of user.
     */
    public $patronymic;

    /**
     * Phone number.
     *
     * @var integer $phone phone number.
     */
    public $phone;

    /**
     * PDO database specimen.
     *
     * @var \PDO $db PDO database specimen.
     */
    public $db;

    /**
     * Serialize an object of identity.
     * Also unsets PDO object because obviously 
     * we don't need to serialize whole database connector.
     *
     * @return array array of serialized fields.
     */
    public function __sleep()
    {
        unset($this->db);
        
        return [
            'email',
            'password',
            'name',
            'surname',
            'patronymic',
            'phone',
        ];
    }

    /** 
     * Login on page if user credentials are set on header 
	 * and "Enter" button is pressed
     * 
     * @return boolean|null true if login is OK 
	 * or null if credentials isn't set or empty (i.e. user don't want to login)
	 */
	public function Login()
	{
		if (null === $this->db) {
			Logger::CatchError(get_class() ."::Login: Attempt to use \$db on null");
		}

		/* User credentials may be already set in session */
		if (isset($_SESSION["Identity"])) {
			$user = $_SESSION["Identity"];
            $this->SetUserCredentials($user);
            
			return true;
        }
		
		if (!(isset($_POST["login_email"]) && isset($_POST["login_password"]))) {
			return false;
		}
		
		if (!($_POST["login_email"] != "" && $_POST["login_password"] != "")) {
			return false;
		}
		
		/* User must exist in DB */
		if ($this->IdentityExists($_POST["login_email"])) {
			/* User password must be compared with password hash in DB */
			if ($this->ComparePassword($_POST["login_email"], $_POST["login_password"])) {
                $credentials = $this->GetUserCredentials($_POST["login_email"]);
                $this->SetUserCredentials($credentials);
				return true;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}

    /**
     * Logout user if user is already logined.
     *
     * @return bool true if user logout operation ended successfully,
     * false in other case.
     */
	public function Logout()
	{
		if (isset($_SESSION["Identity"])) {
			unset($_SESSION["Identity"]);
			return true;
		} else {
			return false;
		}
	}

    /**
     * Compare user password.
     *
	 * @param string $email E-mail of user.
	 * @param string $password Password of user.
     *
     * @return boolean true if passwords are the same,
     * false in other case.
	 */
	private function ComparePassword(string $email, string $expectedPassword)
	{
		$stmt = $this->db->prepare('SELECT password FROM client WHERE email=:email LIMIT 1');
		$stmt->bindParam(':email', $email);
		$stmt->execute();
		
		$user = $stmt->fetch(\PDO::FETCH_OBJ);

        return (hash_equals($user->password, crypt($expectedPassword, $user->password)));
	}

    /**
     * Set user credentials
     *
     * @param mixed $credentials credentials of user: array or an instance of existing identity.
     *
     * @return void
     */
    private function SetUserCredentials($credentials)
    {
        $credentials = (array)$credentials;
        $this->email = $credentials['email'];
        $this->password = $credentials['password'];
        $this->name = $credentials['name'];
        $this->surname = $credentials['surname'];
        $this->patronymic = $credentials['patronymic'];
    }

    /**
     * Get user credentials by his email: name, surname and patronymic.
     *
     * @param string $email e-mail of user.
     *
     * @return array user credentials: name, surname and patronymic.
     */
    private function GetUserCredentials(string $userEmail)
    {
		if (null === $this->db) {
			Logger::CatchError(get_class() ."::Login: Attempt to use \$db on null");
		}
        
        $sql = $this->db->prepare('SELECT * FROM client WHERE email=:user_email');
        $sql->bindParam(':user_email', $userEmail);
        $sql->execute();

        return $sql->fetchAll()[0];
    }

    /**
     * Check if a user with received e-mail is exists in DB 
     * 
     * @param string $email E-mail of user
     *
     * @throws \PDOException if result query contains error information.
     * 
     * @return boolean true if user exists or false in other case.
	 */
	public function IdentityExists(string $email)
	{
		if (null === $email) {
			Logger::CatchError(get_class() ."::UserExists: Attempt to use \$email on null");
		}
		if ('' === $email) {
			Logger::CatchError(get_class() ."::IdentityExists: Attempt to use \$email on empty");
		}
		if (null === $this->db) {
			Logger::CatchError(get_class() ."::IdentityExists: Attempt to use \$db on null");
		}
		
		$sql = $this->db->prepare('SELECT * FROM client WHERE email=:email');
		$sql->bindParam(':email', $email);
		$sql->execute();
		
        return ($sql->rowCount() != 0);
	}

    /**
     * Hash password 
     * 
     * @param string $password Password
     * 
     * @return string Hashed password
	 */
	private function HashPassword(string $password)
	{
		/* Create a random salt */
		$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');
		
		/*	"$2a$" means the Blowfish algorithm,
		 *	the following two digits are the cost parameter.
		 */
		$salt = sprintf("$2a$%02d$", 10) . $salt;
		
		return crypt($password, $salt);
	}
}
