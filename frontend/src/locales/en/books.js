export default {
  books: {
    fields: {
      title: 'Title',
      author: 'Author',
      isbn: 'ISBN',
      publicationDate: 'Publication Date',
      genre: 'Genre',
      numberOfCopies: 'Copies',
    },
    create: {
      success: 'Book added successfully!',
    },
    update: {
      success: 'Book updated successfully!',
    },
    dialogs: {
      delete: {
        content: 'Are you sure want to delete book named "{title}"',
      }
    }
  }
}
