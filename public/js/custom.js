function formatPlate(value) {
    // Keep only letters and numbers
    value = value.toUpperCase().replace(/[^A-Z0-9]/g, '');

    // Split manually with hard limits
    let front  = value.match(/^[A-Z]{0,2}/)?.[0] || '';
    let rest1  = value.slice(front.length);

    let number = rest1.match(/^\d{0,4}/)?.[0] || '';
    let rest2  = rest1.slice(number.length);

    let back   = rest2.match(/^[A-Z]{0,3}/)?.[0] || '';

    let result = front;

    if (number) {
        result += ' ' + number;
    }

    if (back) {
        result += ' ' + back;
    }

    return result;
}
