<?php

namespace App\Services;

class LdapService
{

    public function authenticate($email, $password)
    {
        // 1. Vérifier si les champs ne sont pas vides
        if (empty($email) || empty($password)) {
            return false;
        }

        // 2. Connexion au serveur OpenLDAP
        $connection = ldap_connect($this->host, $this->port);
        if (!$connection) {
            return false;
        }

        // 3. Paramétrage des options LDAP indispensables
        ldap_set_option($connection, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($connection, LDAP_OPT_REFERRALS, 0);

        // 4. Construction du DN de l'utilisateur
        $userDn = "uid=" . $email . ",ou=users," . $this->baseDn;

        // 5. Tentative de liaison (Bind) avec le mot de passe fourni par l'utilisateur
        @$bind = ldap_bind($connection, $userDn, $password);

        if ($bind) {
            
            $filter = "(uid=" . $email . ")";
            $search = ldap_search($connection, "ou=users," . $this->baseDn, $filter);
            $entries = ldap_get_entries($connection, $search);

            ldap_unbind($connection);

            if ($entries['count'] > 0) {
                return [
                    'email' => $email,
                    'cn'    => $entries[0]['cn'][0] ?? $email,
                ];
            }
        }

        ldap_unbind($connection);
        return false;
    }
}