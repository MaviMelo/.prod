<?php



######################## ruotes ################

# ---------------------- web ----------------

<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // return view('welcome');
    return redirect()->route('courses.index');
});

Route::resource('teachers', TeacherController::class);
Route::resource('courses', CourseController::class);








######################## migrations ################

# ---------------------- teachers ----------------


<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();            
            $table->string('name',255);
            $table->string('email',255)->unique();
            $table->string('specialization',500);
            $table->date('hire_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};








# ---------------------- courses ----------------


<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();  // Codigo do curso. Ex.:"ADM_2024.1".
            $table->integer('workload');           // Carga horária total prevista do curso.
            $table->text('description')->nullable();  // Descrisão e ementa do curso.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};


# ---------------------- course_teachers ----------------
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('course_teachers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained();
            $table->foreignId('teacher_id')->constrained();
            $table->string('discipline');  // Disciplina/matéria da aula.
            $table->string('semester');    
            $table->string('classroom')->nullable(); // Sala de aula.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_teachers');
    }
};





######################## Models ################

# ---------------------- Teacher ----------------

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Teacher extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'specialization',
        'hire_date',
    ];

    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, 'course_teachers')
            ->withPivot('discipline', 'semester', 'classroom')
            ->withTimestamps();
    }
}






# ---------------------- Course ----------------


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
        'workload',
        'description',
    ];

    public function teachers(): BelongsToMany
    {
        return $this->belongsToMany(Teacher::class, 'course_teachers')
            ->withPivot('discipline', 'semester', 'classroom')
            ->withTimestamps();
    }
}





# ---------------------- CourseTeacher ----------------

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CourseTeacher extends Pivot
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'course_teachers';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'course_id',
        'teacher_id',
        'discipline',
        'semester',
        'classroom',
    ];

    /**
     * Get the course that owns the relationship.
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Get the teacher that owns the relationship.
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}





######################## Controllers ################

# ---------------------- CategoryController ----------------

<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Teacher;
use Illuminate\Http\Request;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teachers = Teacher::all();
        return view('teachers.index', compact('teachers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $courses = Course::all();
        return view('teachers.create', compact('courses'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:teachers,email',
            'specialization' => 'required|string|max:500',
            'hire_date' => 'required|date',
            'courses.*.course_id' => 'sometimes|exists:courses,id',
            'courses.*.discipline' => 'required_with:courses.*.course_id|string|max:255',
            'courses.*.semester' => 'required_with:courses.*.course_id|string|max:255',
            'courses.*.classroom' => 'nullable|string|max:255',
        ]);

        $teacher = Teacher::create($request->except('courses'));

        if ($request->has('courses')) {
            foreach ($request->input('courses') as $courseData) {
                if (isset($courseData['course_id'])) {
                    $teacher->courses()->attach($courseData['course_id'], [
                        'discipline' => $courseData['discipline'],
                        'semester' => $courseData['semester'],
                        'classroom' => $courseData['classroom'] ?? null,
                    ]);
                }
            }
        }

        return redirect()->route('teachers.index')
            ->with('success', 'Teacher created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Teacher $teacher)
    {
        $teacher->load('courses');
        return view('teachers.show', compact('teacher'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Teacher $teacher)
    {
        $courses = Course::all();
        return view('teachers.edit', compact('teacher', 'courses'));
    }
    
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Teacher $teacher)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:teachers,email,' . $teacher->id,
                'specialization' => 'required|string|max:500',
                'hire_date' => 'required|date',
                'courses.*.course_id' => 'sometimes|exists:courses,id',
                'courses.*.discipline' => 'required_with:courses.*.course_id|string|max:255',
                'courses.*.semester' => 'required_with:courses.*.course_id|string|max:255',
                'courses.*.classroom' => 'nullable|string|max:255',
            ]);
        
            $teacher->update($request->except('courses'));
        
            if ($request->has('courses')) {
                $syncData = [];
                foreach ($request->input('courses') as $courseData) {
                    if (isset($courseData['course_id'])) {
                        $syncData[$courseData['course_id']] = [
                            'discipline' => $courseData['discipline'],
                            'semester' => $courseData['semester'],
                            'classroom' => $courseData['classroom'] ?? null,
                        ];
                    }
                }
                $teacher->courses()->sync($syncData);
            } else {
                $teacher->courses()->detach();
            }
        
            return redirect()->route('teachers.index')
                ->with('success', 'Teacher updated successfully.');
        } catch (\Exception $e) {
            dd($e->getMessage(), $e->getTraceAsString());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Teacher $teacher)
    {
        $teacher->delete();

        return redirect()->route('teachers.index')
            ->with('success', 'Teacher deleted successfully.');
    }
}






# ---------------------- ProductController ----------------

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index()
    {
        // Carregar os produtos com a categoria usando eager loading
        $products = Product::with('category')->get();

        return view('products.index', compact('products'));
    }

    public function create()
    {   
        $categories = Category::all();

        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        Product::create($request->all());
        return redirect()->route('products.index')->with('success', 'Produto criado com sucesso.');
    }

    public function show(Product $product)
    {
        // Carregar o produto com a categoria usando eager loading
        $product->load(['category']);

        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();

        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $product->update($request->all());

        return redirect()->route('products.index')->with('success', 'Produto atualizado com sucesso.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Produto excluido com sucesso.');
    }
}







