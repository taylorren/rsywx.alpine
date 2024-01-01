window.formatDate = function(dateString) {
  const date = new Date(dateString);
  const year = date.getFullYear();
  const month = date.getMonth() + 1; // Months are 0-based in JavaScript
  const day = date.getDate();

  return `${year}年${month.toString().padStart(2, '0')}月${day.toString().padStart(2, '0')}日`;
}

window.formatThousand = function(num) {
  return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}