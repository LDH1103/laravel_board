const apiForm = document.getElementById('apiForm');
const apiData = document.getElementById('apiData');

apiForm.addEventListener('submit', (event) => {
    event.preventDefault(); // 기본 동작 방지 (폼 제출 시 페이지 새로고침 방지)

    const urlInput = document.getElementById('url').value;
    const methodInput = document.getElementById('method').value;

    // API 요청 보내기
    fetch(urlInput, {
        method : methodInput, // 셀렉트박스에서 선택한 메소드 지정
        headers : {
            // X-Requested-With 헤더 설정
            // AJAX 요청을 식별하기 위해 사용되는 표준이 아닌 헤더
            // 웹 애플리케이션에서 AJAX 요청을 보낼 때 이 헤더를 설정하여 서버에서 해당 요청이 AJAX로 보내진 것인지 확인할 수 있음
            "X-Requested-With": "XMLHttpRequest" 
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error(response.statusText);
            }
            return response.json(); // 응답 데이터를 JSON 형식으로 변환하여 반환
        })
        .then(data => {
            // 성공적으로 응답을 받았을 때의 처리
            // apiData.innerHTML = JSON.stringify(data); // 데이터를 문자열로 변환하여 출력
            let formattedData = '';
            // Object.entries(data) : 객체의 속성과 값으로 구성된 배열을 반환
            Object.entries(data).forEach(([key, value]) => {
                formattedData += `${key}: ${value}<br>`;
            });
            apiData.innerHTML = formattedData; // 형식화된 데이터를 출력
        })
        .catch(error => {
            // 에러 발생 시의 처리
            apiData.innerHTML = error;
        });
});