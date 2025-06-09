
document.addEventListener('DOMContentLoaded', function () {
    const startTime = Date.now();

    document.getElementById('amo_form').addEventListener('submit', function(e) {
        e.preventDefault(); 

        const form = this;
        const formData = new FormData(form);

        const duration = Date.now() - startTime;
        const isLongVisit = duration > 30000;

        formData.append('is_long_visit', isLongVisit ? '1' : '0');

        fetch('formhandler.php', { 
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            console.log('Ответ от сервера:', data);
            document.getElementById('response').innerText = data.message || 'Данные успешно отправлены';
        })
        .catch(error => {
            console.error('Ошибка:', error);
            document.getElementById('response').innerText = 'Произошла ошибка при отправке данных.';
        });
    });
});