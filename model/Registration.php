<?php

namespace model;

use core;

/**
 * Class Registration.
 * Registers user in the site.
 */
class Registration
{
    /**
     * User identity.
     *
     * @var Identity $identity user identity.
     */
    public $identity;

    /**
     * PDO database specimen.
     *
     * @var PDO $db PDO database specimen.
     */
    public $db;

    /**
     * Registers user in the system.
     *
     * @return bool true if user registered successfully;
     * false in other case.
     */
    public function Register()
    {
        if (null === $this->db) {
            \core\Logger::CatchError(get_class() . '::Register: Attempt to use \$db on null');
            return false;
        }
        if (null === $this->identity) {
            core\Logger::CatchError(get_class() . '::Register: Attempt to use \$identity on null');
            return false;
        }

        if (false === $this->InsertInUser()) {
            return false;
        }

        return true;
    }

    /**
     * Inserts identity values in 'user' table.
     *
     * @throws PDOException in case of PDO error.
     *
     * @return bool true if insert was successful;
     * false in case of PDO error.
     */
    private function InsertInUser()
    {
        $sql = $this->db->prepare('INSERT INTO client(email, password, name, surname, patronymic, pasport_seria, pasport_number) VALUES(:email, :password, :name, :surname, :patronymic, :passport_seria, :passport_number)');
        $sql->bindParam(':email', $this->identity->email);
        $password = $this->HashPassword($this->identity->password);
        $sql->bindParam(':password', $password);
        $sql->bindParam(':name', $this->identity->name);
        $sql->bindParam(':surname', $this->identity->surname);
        $sql->bindParam(':patronymic', $this->identity->patronymic);
        $passport = $this->GeneratePassportCredentials();
        $sql->bindParam(':passport_seria', $passport[0]);
        $sql->bindParam(':passport_number', $passport[1]);

        try {
            $sql->execute();
        } catch (PDOException $e) {
            core\Logger::CatchError(get_class() ."::InsertInUser: PDOException was throwed during DB insertion. Information about error: {$e->errorInfo()[2]}");
            return false;
        }

        return true;
    }

    /**
     * Generates passport credentials: seria and number.
     *
     * @return array passport credentials.
     */
    private function GeneratePassportCredentials()
    {
        $passportCredentials = [
            '',
            '',
        ];
        for ($i = 0; $i < 5; $i++) {
            $passportCredentials[0] .= rand(0, $i);
        }
        for ($i = 0; $i < 7; $i++) {
            $passportCredentials[1] .= rand(0, $i);
        }

        return $passportCredentials;
    }

    /**
     * Hash password of user.
     *
	 * @param string $password Password.
     *
	 * @return string Hashed password.
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
