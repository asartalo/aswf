# Continuous testing using Guard
#
# More info at https://github.com/guard/guard#readme
#

def all_tests
  Dir.glob('tests/**/*.php')
end

# Install phpunit-guard to make this work
guard 'phpunit', cli: '--colors', tests_path: 'tests', all_after_pass: true do
  watch(%r{^.+Test\.php$})

  # Watch library files and run their tests
  watch(%r{^src/(\w+)/(.+)\.php}) do |m|
    file = "tests/#{m[1]}/Tests/Unit/#{m[2]}Test.php"
    if File.exist? file
      file
    else
      all_tests
    end
  end
  watch(%r{^tests/.+\.(php|yml)}) { |m| all_tests unless m[0].match /.+Test.php/ }
end