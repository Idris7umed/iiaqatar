# Contributing to IIAQATAR

Thank you for considering contributing to IIAQATAR! We welcome contributions from the community.

## Code of Conduct

By participating in this project, you agree to maintain a respectful and collaborative environment.

## How to Contribute

### Reporting Bugs

If you find a bug, please create an issue with:
- A clear title and description
- Steps to reproduce the issue
- Expected vs actual behavior
- Screenshots (if applicable)
- Your environment details (OS, PHP version, etc.)

### Suggesting Enhancements

We welcome suggestions for new features or improvements:
- Open an issue with the "enhancement" label
- Clearly describe the feature and its benefits
- Provide examples of how it would work

### Pull Requests

1. Fork the repository
2. Create a new branch (`git checkout -b feature/amazing-feature`)
3. Make your changes
4. Write or update tests as needed
5. Ensure all tests pass (`php artisan test`)
6. Format your code with Laravel Pint (`./vendor/bin/pint`)
7. Commit your changes (`git commit -m 'Add amazing feature'`)
8. Push to the branch (`git push origin feature/amazing-feature`)
9. Open a Pull Request

### Coding Standards

- Follow PSR-12 coding standards
- Use Laravel best practices
- Write clear, descriptive commit messages
- Add comments for complex logic
- Keep methods small and focused
- Write tests for new features

### Database Changes

When adding database migrations:
- Use descriptive migration names
- Include both `up()` and `down()` methods
- Test migrations thoroughly
- Document any complex changes

### Frontend Changes

- Follow the existing Tailwind CSS patterns
- Ensure responsive design
- Test across different browsers
- Maintain accessibility standards

## Development Setup

See the main README.md for detailed installation instructions.

## Testing

Run the test suite before submitting pull requests:

```bash
php artisan test
```

## Questions?

Feel free to reach out via:
- GitHub Issues
- Email: dev@iiaqatar.org

Thank you for contributing!
