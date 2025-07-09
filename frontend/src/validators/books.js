import * as yup from 'yup'

/**
 * Validates ISBN-10 or ISBN-13.
 * Accepts digits, spaces, and hyphens.
 */
function isValidISBN(value) {
  if (!value) return false;

  // 1 ─ Remove separators
  const clean = value.replace(/[-\s]/g, '');

  // 2 ─ Length / allowed chars
  const isbn10Pattern = /^\d{9}[\dX]$/;   // final “X” allowed
  const isbn13Pattern = /^\d{13}$/;

  if (!isbn10Pattern.test(clean) && !isbn13Pattern.test(clean)) return false;

  // 3 ─ ISBN-10 checksum
  if (clean.length === 10) {
    let sum = 0;
    for (let i = 0; i < 9; i++) sum += (i + 1) * Number(clean[i]);
    const checkDigit = clean[9] === 'X' ? 10 : Number(clean[9]);
    sum += 10 * checkDigit;
    return sum % 11 === 0;
  }

  // 4 ─ ISBN-13 checksum
  let sum = 0;
  for (let i = 0; i < 12; i++) {
    sum += Number(clean[i]) * (i % 2 === 0 ? 1 : 3);
  }
  const checkDigit = (10 - (sum % 10)) % 10;
  return checkDigit === Number(clean[12]);
}

export const bookSchema = yup.object({
  title: yup
    .string()
    .required('Title is required')
    .min(2, 'Title is too short'),

  author: yup
    .string()
    .required('Author is required')
    .min(2, 'Author name is too short'),

  isbn: yup
    .string()
    .required('ISBN is required')
    .test('isbn-checksum', 'Invalid ISBN-10 or ISBN-13', isValidISBN),

  publicationDate: yup
    .date()
    .typeError('Publication date is required')
    .required('Publication date is required'),

  genre: yup
    .string()
    .required('Genre is required'),

  numberOfCopies: yup
    .number()
    .typeError('Number of copies must be a number')
    .required('Number of copies is required')
    .min(1, 'Must be at least 1 copy'),
})
