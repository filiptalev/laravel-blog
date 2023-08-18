export default function ({route, redirect, $axios,  $auth}) {
    let is_admin = "";

    
    if ($auth.user.role == 'administrator') {
        is_admin = 1;
    }
    
    if(is_admin != 1) {
        redirect('/dashboard');
    }


}
