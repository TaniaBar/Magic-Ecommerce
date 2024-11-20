import './bootstrap.js';
/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import "./vendor/bootstrap/dist/css/bootstrap.min.css";
import './styles/app.css';
import './styles/header.css';
import './styles/footer.css';
import './styles/home.css';
import './styles/login.css';
import './styles/produit.css';
import './styles/nous.css';
import './styles/register.css';
import './styles/modifProfil.css';
import './styles/addProductForm.css';
import './styles/userProfile.css';
import './styles/resetPassword.css';

console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

document.addEventListener('DOMContentLoaded', function() {
    const roleSelectors = document.querySelectorAll('.role-selector');
    const companyFields = document.getElementById('company-fields');

    roleSelectors.forEach(function (roleSelector) {
        roleSelector.addEventListener('change', function() {
            if (this.value === 'ROLE_ENTREPRISE') {
                companyFields.style.display = 'block';
            } else {
                companyFields.style.display = 'none';
            }
        });
    });
})

document.addEventListener('DOMContentLoaded', function() {
    const navbarToggler = document.querySelector('.navbar-toggler');
    const navbarCollapse = document.querySelector('#navbarSupportedContent');
    
    navbarToggler.addEventListener('click', () => {
        navbarCollapse.classList.toggle('show');
    });
});