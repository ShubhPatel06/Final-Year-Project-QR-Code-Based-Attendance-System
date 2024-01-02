class LectureList {
  final List<LectureData> data;

  LectureList({required this.data});

  factory LectureList.fromJson(Map<String, dynamic> json) {
    return LectureList(
      data: (json['data'] as List<dynamic>)
          .map((item) => LectureData.fromJson(item))
          .toList(),
    );
  }
}

class LectureData {
  final int admNo;
  final int lectureId;
  final int groupId;
  final Group group;
  final Lecture lecture;
  final List<LecturerAllocation> lecturerAllocation;

  LectureData({
    required this.admNo,
    required this.lectureId,
    required this.groupId,
    required this.group,
    required this.lecture,
    required this.lecturerAllocation,
  });

  factory LectureData.fromJson(Map<String, dynamic> json) {
    return LectureData(
      admNo: json['adm_no'] as int,
      lectureId: json['lecture_id'] as int,
      groupId: json['group_id'] as int,
      group: Group.fromJson(json['group'] as Map<String, dynamic>),
      lecture: Lecture.fromJson(json['lecture'] as Map<String, dynamic>),
      lecturerAllocation: (json['lecturer_allocation'] as List<dynamic>)
          .map((item) => LecturerAllocation.fromJson(item))
          .toList(),
    );
  }
}

class Group {
  final int groupId;
  final String groupName;
  final int year;
  final int semester;
  final int divisionId;

  Group({
    required this.groupId,
    required this.groupName,
    required this.year,
    required this.semester,
    required this.divisionId,
  });

  factory Group.fromJson(Map<String, dynamic> json) {
    return Group(
      groupId: json['group_id'] as int,
      groupName: json['group_name'] as String,
      year: json['year'] as int,
      semester: json['semester'] as int,
      divisionId: json['division_id'] as int,
    );
  }
}

class Lecture {
  final int lectureId;
  final String lectureCode;
  final String lectureName;
  final int courseId;
  final int totalHours;

  Lecture({
    required this.lectureId,
    required this.lectureCode,
    required this.lectureName,
    required this.courseId,
    required this.totalHours,
  });

  factory Lecture.fromJson(Map<String, dynamic> json) {
    return Lecture(
      lectureId: json['lecture_id'] as int,
      lectureCode: json['lecture_code'] as String,
      lectureName: json['lecture_name'] as String,
      courseId: json['course_id'] as int,
      totalHours: json['total_hours'] as int,
    );
  }
}

class LecturerAllocation {
  final int allocationId;
  final int lecturerId;
  final int lectureId;
  final int groupId;
  final Lecturer lecturer;

  LecturerAllocation({
    required this.allocationId,
    required this.lecturerId,
    required this.lectureId,
    required this.groupId,
    required this.lecturer,
  });

  factory LecturerAllocation.fromJson(Map<String, dynamic> json) {
    return LecturerAllocation(
      allocationId: json['allocation_id'] as int,
      lecturerId: json['lecturer_id'] as int,
      lectureId: json['lecture_id'] as int,
      groupId: json['group_id'] as int,
      lecturer: Lecturer.fromJson(json['lecturer'] as Map<String, dynamic>),
    );
  }
}

class Lecturer {
  final int userId;
  final int facultyId;
  final User user;

  Lecturer({
    required this.userId,
    required this.facultyId,
    required this.user,
  });

  factory Lecturer.fromJson(Map<String, dynamic> json) {
    return Lecturer(
      userId: json['user_id'] as int,
      facultyId: json['faculty_id'] as int,
      user: User.fromJson(json['user'] as Map<String, dynamic>),
    );
  }
}

class User {
  final int userId;
  final String firstName;
  final String lastName;
  final String username;
  final String email;
  final String phoneNo;
  final int roleId;

  User({
    required this.userId,
    required this.firstName,
    required this.lastName,
    required this.username,
    required this.email,
    required this.phoneNo,
    required this.roleId,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      userId: json['user_id'] as int,
      firstName: json['first_name'] as String,
      lastName: json['last_name'] as String,
      username: json['username'] as String,
      email: json['email'] as String,
      phoneNo: json['phoneNo'] as String,
      roleId: json['role_id'] as int,
    );
  }
}
