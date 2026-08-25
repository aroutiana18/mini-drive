<?php
namespace App\Models;

class UserModel {

    private string $ldapHost = '127.0.0.1';
    private int $ldapPort = 389;
    private string $ldapBase = 'ou=users,dc=l2eni,dc=mg';

    /**
     * Authentifie un utilisateur directement contre LDAP.
     */
    public function authenticate(string $email, string $password): ?array
    {
        $email = trim($email);

        if ($email === '' || $password === '') {
            return null;
        }

        $ldap = ldap_connect($this->ldapHost, $this->ldapPort);

        if ($ldap === false) {
            return null;
        }

        ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);

        /*
         * Recherche de l'utilisateur par son adresse email.
         */
        $filter = '(&(objectClass=inetOrgPerson)(mail=' . ldap_escape(
            $email,
            '',
            LDAP_ESCAPE_FILTER
        ) . '))';

        $search = ldap_search(
            $ldap,
            $this->ldapBase,
            $filter,
            ['dn', 'mail', 'uid', 'cn']
        );

        if ($search === false) {
            ldap_unbind($ldap);
            return null;
        }

        $entries = ldap_get_entries($ldap, $search);

        if ($entries['count'] !== 1) {
            ldap_unbind($ldap);
            return null;
        }

        $userDn = $entries[0]['dn'];

        /*
         * Vérification du mot de passe :
         * on tente un bind LDAP avec le DN de l'utilisateur.
         */
        if (!@ldap_bind($ldap, $userDn, $password)) {
            ldap_unbind($ldap);
            return null;
        }

        $user = [
            'email' => $entries[0]['mail'][0] ?? $email,
            'username' => $entries[0]['uid'][0] ?? $email,
            'name' => $entries[0]['cn'][0] ?? $email
        ];

        ldap_unbind($ldap);

        return $user;
    }
}