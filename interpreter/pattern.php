<?php


//The Interpreter Design Pattern analyzes an entity for key elements and provides its own
//interpretation or action corresponding to each key.

class User
{
    protected $_username = '';

    public function __construct($username)
    {
        $this->_username = $username;
    }

    public function getProfilePage()
    {
        //In lieu of getting the info from the DB, we mock here
        $profile = " <h2> I like Never Again! </h2> ";
        $profile .= "I love all of their songs . My favorite CD: <br> ";
        $profile .= "{{myCD.getTitle}}!!";

        return $profile;
    }
}

class userCD
{
    protected $_user = null;

    public function setUser($user)
    {
        $this->_user = $user;
    }

    public function getTitle()
    {
        //mock here
        $title = 'Waste of a Rib';

        return $title;
    }
}

class userCDInterpreter
{
    protected $_user = null;

    public function setUser($user)
    {
        $this->_user = $user;
    }

    public function getInterpreted()
    {
        $profile = $this->_user->getProfilePage();

        if (preg_match_all('/\{\{myCD\.(.*?)\}\}/', $profile,
            $triggers, PREG_SET_ORDER)) {
            $replacements = array();

            foreach ($triggers as $trigger) {
                $replacements[] = $trigger[1];
            }

            $replacements = array_unique($replacements);

            $myCD = new userCD();
            $myCD->setUser($this->_user);

            foreach ($replacements as $replacement) {
                $profile = str_replace("{{myCD .{$replacement}}}",
                    call_user_func(array($myCD, $replacement)), $profile);
            }

        }

        return $profile;
    }
}

$username = 'aaron';
             
$user = new User($username);
$interpreter = new userCDInterpreter();
$interpreter->setUser($user);
             
print " <h1> {$username}'s Profile  </h1>";
print $interpreter->getInterpreted(); 