1. require_login() sees if the session is empty. If it is then go back to the login page. It cheeks everytime you go to a new page besides the login and register-form page

2. It first ask the user for their username and password. When the user click the button it will set the $action variable to 'login'. It will then go to the login case. The login case will first trim the password and username that the user sent. If username and password is not falsey then find and get the user info in the db by their username. It will verify the password. Finally it will give the SESSION the user id and full name and send the user to the list view.  If the username and password WAS falsey or send empty fields then display a messsage.

3. When you click add to cart, the record id of that row gets stored in the $_SESSION['cart'].  'add_to_cart' is the action that add items to the cart.

4. The $records_in_cart variable comes from if($view == 'cart') statement in the index page. We need records_by_ids() method instead of using raw ids because the method grabs the information that we will need for the cart view like the title and price of the records

5. When the user click “Complete Purchase.” it will send send a post request with the name as action and the value as checkout. checkout case will insert a new record into the purchases table foreach cart id. It will then clear $_SESSION['cart'] and send the user to the checkout_success view. 
