import './bootstrap';
import '../css/app.css';

fetch('http://127.0.0.1:8000/api/register', {
    method: 'POST',
    headers: {
        'Authorization': 'Bearer YOUR_ACCESS_TOKEN',
        'Content-Type': 'application/json'
    },
    body: JSON.stringify({
        name: 'test',
        email: 'test2@gmail.com',
        password: 'test1234',
        password_confirmation: 'test1234'
    })
})
    .then(response => response.json())
    .then(data => console.log(data))
    .catch(error => console.error('Error:', error));
