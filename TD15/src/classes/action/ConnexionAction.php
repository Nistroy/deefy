<?php

namespace iutnc\deefy\action;

abstract class ConnexionAction extends Action
{
    private string $buttonText;
    private string $secondChoiceText;

    private string $secondChoiceAction;

    private bool $isSignIn;

    private bool $isConnected = false;

    public function __construct(string $buttonText, string $secondChoiceText, string $secondChoiceAction, bool $isSignIn)
    {
        parent::__construct();
        $this->buttonText = $buttonText;
        $this->secondChoiceText = $secondChoiceText;
        $this->secondChoiceAction = $secondChoiceAction;
        $this->isSignIn = $isSignIn;

        if (isset($_SESSION['user']['id'])) {
            $this->isConnected = $_SESSION['user']['id'] != null;
        }
    }


    public function execute(): string
    {
        if ($this->isConnected) {
            return '<h3>Vous êtes déjà connecté</h3>
                    <a href="?action=sign-out">Se déconnecter</a>';
        }



        if ($this->http_method === "GET") {
            $confirmPassword = '';
            if (!$this->isSignIn)
            {
                $confirmPassword = '<input type="text" name="passwd2" placeholder="confirmer mot de passe">';
            }

            $res =
                '<form method="post" action="?">
                    <input type="email" name="email" placeholder="email" autofocus>
                    <input type="text" name="password" placeholder="mot de passe">
                    ' . $confirmPassword . '
                    <div class="signInOrUp">
                        <input type="submit" name="connex" value="' . $this->buttonText . '">
                        <a href="' . $this->secondChoiceAction . '">' . $this->secondChoiceText . '</a>
                    </div>
                </form>';
        } else {
            $res = $this->getConnection();
        }

        return $res;
    }

    public function isConnected(): bool
    {
        return $this->isConnected;
    }

    abstract function getConnection(): string;
}