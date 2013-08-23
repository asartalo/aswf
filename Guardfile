# Continuous testing using Guard
#
# More info at https://github.com/guard/guard#readme
#


# Install phpunit-guard to make this work
guard 'phpunit', cli: '--colors', tests_path: 'tests', all_after_pass: true do
  watch(%r{^.+Test\.php$})

  # Watch library files and run their tests
  watch(%r{^src/Asar/(.+)\.php}) { |m| "tests/Asar/Tests/Unit/#{m[1]}Test.php" }
  watch(%r{^tests/.+\.(php|yml)}) do |m|
    unless m[0].match /.+Test.php/
      Dir.glob('tests/**/*.php')
    end
  end
end
