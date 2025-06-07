document.getElementById('amo_form').addEventListener('submit', function(e) {
    e.preventDefault(); 

    const form = this;
    const formData = new FormData(form);

    fetch('formhandler.php', { 
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        console.log('Ответ от сервера:', data);
        document.getElementById('response').innerText = data.message || 'Данные успешно отправлены';
    })
    .catch(error => {
        console.error('Ошибка:', error);
        document.getElementById('response').innerText = 'Произошла ошибка при отправке данных.';
    });
});