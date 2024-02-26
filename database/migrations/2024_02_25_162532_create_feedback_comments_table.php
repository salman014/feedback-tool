<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbackCommentsTable extends Migration
{
    public function up()
    {
        Schema::create('feedback_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('feedback_id');
            $table->text('content');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('feedback_id')->references('id')->on('feedbacks')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('feedback_comments');
    }
}
