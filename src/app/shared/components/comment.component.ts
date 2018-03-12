import {Component, OnInit} from "@angular/core";
import {Art} from "../classes/art";
import {Profile} from"../classes/profile"
import {Comment} from"../classes/comment"
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

	comment: Comment = new Comment(null, null, null, null, null);

	comments: Comment[] = [];
	status: Status = null;

	constructor(
		private commentService: CommentService,
		private route: ActivatedRoute,
	) {}

	ngOnInit() : void {
	}

	getComment() : void{
		let commentArtId : string  = this.route.snapshot.params["artId"];
		//All of this code is not really working, as you can tell. We have questions about if we are subscribing to comment. WHat is the code below doing?
		this.commentService.getCommentByCommentArtId(commentArtId)
			.subscribe(comment => {
				this.comment = comment;
			});
	}
}