$router->get('/booking/getAvailableSlots', 'BookingController@getAvailableSlots'); 
$router->post('/booking/validate-promo', 'BookingController@validatePromo'); 
$router->get('/admin/reports', 'ReportsController@index');