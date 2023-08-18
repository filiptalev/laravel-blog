export default function ({route, redirect, $axios,  $auth}) {
    let is_admin = "";
    let is_manager = "";
    
    if ($auth.user.role == 'administrator') {
        is_admin = 1;
    }

    if ($auth.user.role == 'manager') {
        is_manager = 1;
    }    

    if (is_admin != 1 || is_manager != 1 ) {
        redirect ('/dashboard');
    }

    if(!is_admin == 1) {
        redirect('/dashboard');
    }


}
