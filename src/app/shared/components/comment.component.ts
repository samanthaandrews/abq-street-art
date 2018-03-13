import {Component, OnInit} from "@angular/core";
import {FormBuilder, FormGroup, Validators} from "@angular/forms";

import {Art} from "../classes/art";
import {Profile} from"../classes/profile"
import {Comment} from"../classes/comment"

import {AuthService} from "../services/auth.service";
import {ArtService} from "../services/art.service";
import {ProfileService} from "../services/profile.service";
import {CommentService} from "../services/comment.service";

import {Status} from "../classes/status";
import {ActivatedRoute, Params} from "@angular/router";

@Component({
	template: require("./comment.component.html"),
	selector: "comment"
})

export class CommentComponent implements OnInit {

//do we need this??
	comment: Comment = new Comment(null, null, null, null, null);

    createCommentForm: FormGroup;
	comments: Comment[] = [];
	status: Status = null;

	constructor(
		private formBuilder: FormBuilder,
		private authService: AuthService,
		private commentService: CommentService,
		private artService: ArtService,
		private profileService: ProfileService,
		private route: ActivatedRoute,
	) {}

	ngOnInit() : void {
		this.listComments();

		this.createCommentForm = this.formBuilder.group({
			commentContent: ["", [Validators.maxLength(2000), Validators.required]]
		});

	}


	listComments() : any {

        let commentArtId : string  = this.route.snapshot.params["commentArtId"];

		this.commentService.getCommentByCommentArtId(commentArtId)
			.subscribe(comments => this.comments = comments);
	}

	getJwtProfileId() : any {
		if(this.authService.decodeJwt()) {
			return this.authService.decodeJwt().auth.profileId;
		} else {
			return false
		}
	}

	createComment() : any {
		if(!this.getJwtProfileId()) {
			return false
		}

		let newCommentProfileId = this.getJwtProfileId();

		let comment = new Comment(null, null, null, this.createCommentForm.value.commentContent, null);

		this.commentService.createComment(comment)
			.subscribe(status => {
				this.status = status;
				if(status.status === 200) {
					this.listComments();
					this.createCommentForm.reset();
				} else {
					return false
				}
			})
	}

}