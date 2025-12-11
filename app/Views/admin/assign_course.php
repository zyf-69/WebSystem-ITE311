<?= $this->extend('template') ?>

<?= $this->section('content') ?>
<div class="container mt-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1><i class="bi bi-person-check"></i> Assign Course to Teacher</h1>
                <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Dashboard
                </a>
            </div>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle"></i> <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle"></i> <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0">Assign Course</h5>
                </div>
                <div class="card-body">
                    <form method="post" action="<?= base_url('admin/assign-course') ?>">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="course_id" class="form-label">Select Course <span class="text-danger">*</span></label>
                                <select class="form-select <?= isset($validation) && $validation->hasError('course_id') ? 'is-invalid' : '' ?>" 
                                        id="course_id" 
                                        name="course_id" 
                                        required>
                                    <option value="">Select Course</option>
                                    <?php foreach ($unassignedCourses as $course): ?>
                                        <option value="<?= $course['id'] ?>" <?= old('course_id') == $course['id'] ? 'selected' : '' ?>>
                                            <?= esc($course['title']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (isset($validation) && $validation->hasError('course_id')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('course_id') ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="instructor_id" class="form-label">Select Teacher <span class="text-danger">*</span></label>
                                <select class="form-select <?= isset($validation) && $validation->hasError('instructor_id') ? 'is-invalid' : '' ?>" 
                                        id="instructor_id" 
                                        name="instructor_id" 
                                        required>
                                    <option value="">Select Teacher</option>
                                    <?php foreach ($teachers as $teacher): ?>
                                        <option value="<?= $teacher['id'] ?>" <?= old('instructor_id') == $teacher['id'] ? 'selected' : '' ?>>
                                            <?= esc($teacher['name']) ?> (<?= esc($teacher['email']) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (isset($validation) && $validation->hasError('instructor_id')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('instructor_id') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Course Schedule Days</label>
                            <div class="row">
                                <?php 
                                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
                                $oldDays = old('schedule_days') ?: [];
                                foreach ($days as $day): 
                                ?>
                                    <div class="col-md-3 mb-2">
                                        <div class="form-check">
                                            <input class="form-check-input" 
                                                   type="checkbox" 
                                                   name="schedule_days[]" 
                                                   value="<?= $day ?>" 
                                                   id="day_<?= strtolower($day) ?>"
                                                   <?= in_array($day, $oldDays) ? 'checked' : '' ?>>
                                            <label class="form-check-label" for="day_<?= strtolower($day) ?>">
                                                <?= $day ?>
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="start_time" class="form-label">Start Time</label>
                                <input type="time" 
                                       class="form-control <?= isset($validation) && $validation->hasError('start_time') ? 'is-invalid' : '' ?>" 
                                       id="start_time" 
                                       name="start_time" 
                                       value="<?= old('start_time') ?>">
                                <?php if (isset($validation) && $validation->hasError('start_time')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('start_time') ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="end_time" class="form-label">End Time</label>
                                <input type="time" 
                                       class="form-control <?= isset($validation) && $validation->hasError('end_time') ? 'is-invalid' : '' ?>" 
                                       id="end_time" 
                                       name="end_time" 
                                       value="<?= old('end_time') ?>">
                                <?php if (isset($validation) && $validation->hasError('end_time')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('end_time') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="room" class="form-label">Room/Classroom</label>
                                <input type="text" 
                                       class="form-control <?= isset($validation) && $validation->hasError('room') ? 'is-invalid' : '' ?>" 
                                       id="room" 
                                       name="room" 
                                       value="<?= old('room') ?>"
                                       placeholder="e.g., Room 101">
                                <?php if (isset($validation) && $validation->hasError('room')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('room') ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="start_date" class="form-label">Start Date</label>
                                <input type="date" 
                                       class="form-control <?= isset($validation) && $validation->hasError('start_date') ? 'is-invalid' : '' ?>" 
                                       id="start_date" 
                                       name="start_date" 
                                       value="<?= old('start_date') ?>">
                                <?php if (isset($validation) && $validation->hasError('start_date')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('start_date') ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="end_date" class="form-label">End Date</label>
                                <input type="date" 
                                       class="form-control <?= isset($validation) && $validation->hasError('end_date') ? 'is-invalid' : '' ?>" 
                                       id="end_date" 
                                       name="end_date" 
                                       value="<?= old('end_date') ?>">
                                <?php if (isset($validation) && $validation->hasError('end_date')): ?>
                                    <div class="invalid-feedback">
                                        <?= $validation->getError('end_date') ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Assign Course
                            </button>
                            <a href="<?= base_url('dashboard') ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-x-circle"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Assigned Courses Table -->
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0">Assigned Courses</h5>
                </div>
                <div class="card-body">
                    <?php if (empty($allCourses)): ?>
                        <p class="text-muted">No courses assigned yet.</p>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Course</th>
                                        <th>Teacher</th>
                                        <th>Schedule</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($allCourses as $course): ?>
                                        <?php if (!empty($course['instructor_name'])): ?>
                                            <tr>
                                                <td>
                                                    <strong><?= esc($course['title']) ?></strong>
                                                    <?php if (!empty($course['level'])): ?>
                                                        <br><small class="text-muted"><?= esc($course['level']) ?></small>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <span class="badge bg-success"><?= esc($course['instructor_name']) ?></span>
                                                </td>
                                                <td>
                                                    <?php if (!empty($course['start_time']) && !empty($course['end_time'])): ?>
                                                        <i class="bi bi-clock"></i>
                                                        <?= date('h:i A', strtotime($course['start_time'])) ?> - 
                                                        <?= date('h:i A', strtotime($course['end_time'])) ?>
                                                        <?php if (!empty($course['schedule_days'])): ?>
                                                            <br><small class="text-muted"><?= esc($course['schedule_days']) ?></small>
                                                        <?php endif; ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">Not Scheduled</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php 
                                                    $status = $course['status'] ?? 'active';
                                                    $statusClass = $status === 'active' ? 'success' : ($status === 'inactive' ? 'secondary' : 'warning');
                                                    ?>
                                                    <span class="badge bg-<?= $statusClass ?>"><?= ucfirst(esc($status)) ?></span>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

